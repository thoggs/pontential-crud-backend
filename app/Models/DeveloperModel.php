<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class DeveloperModel extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'developers';
    protected $fillable = ['firstName', 'lastName', 'email', 'gender', 'age', 'hobby', 'birthDate'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dateFormat = 'Y-m-d';
    public $timestamps = true;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string)Uuid::uuid4();
            }
            $model->formatAttributes();
        });

        static::updating(function ($model) {
            $model->formatAttributes();
        });
    }

    private function formatAttributes(): void
    {
        $this->firstName = ucfirst(strtolower($this->firstName));
        $this->lastName = ucfirst(strtolower($this->lastName));
        $this->email = strtolower($this->email);
        $this->gender = strtolower($this->gender);
        $this->hobby = ucfirst(strtolower($this->hobby));
        $this->age = (int)$this->age;
    }

    public function searchByTerms(Request $request): Paginator
    {
        $query = DB::table($this->table);
        $columns = Schema::getColumnListing($this->table);

        $searchTerm = $request->input('searchTerm');
        $sorting = $request->input('sorting');

        if (!empty($searchTerm)) {
            $query->where(function ($query) use ($columns, $searchTerm) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'ilike', '%' . $searchTerm . '%');
                }
            });
        }

        if (!empty($sorting) && is_string($sorting)) {
            $sorting = json_decode($sorting, true);
            if (is_array($sorting)) {
                foreach ($sorting as $sort) {
                    if (isset($sort['id']) && isset($sort['desc'])) {
                        $sortColumn = $sort['id'];
                        $sortDesc = $sort['desc'] ? 'desc' : 'asc';
                        if (in_array($sortColumn, $columns)) {
                            $query->orderBy($sortColumn, $sortDesc);
                        }
                    }
                }
            }
        } else {
            $query->orderBy('firstName');
        }

        return $query->select('id', 'firstName', 'lastName', 'email', 'gender', 'age', 'hobby', 'birthDate')
            ->orderBy('firstName')
            ->paginate($request->input('perPage', 50));
    }

    public function createDeveloper(Request $request): DeveloperModel
    {
        return $this->create([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender', 'prefer-not-to-say'),
            'age' => $request->input('age'),
            'hobby' => $request->input('hobby'),
            'birthDate' => $request->input('birthDate')
        ]);
    }

    public function updateDeveloper(Request $request, string $id): void
    {
        $developer = $this::find($id);
        if ($developer) {
            $developer->fill([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'gender' => $request->input('gender', 'prefer-not-to-say'),
                'age' => $request->input('age'),
                'hobby' => $request->input('hobby'),
                'birthDate' => $request->input('birthDate')
            ]);
            $developer->formatAttributes();
            $developer->save();
        }
    }

    public function deleteDeveloperById(string $id): void
    {
        $developer = $this::find($id);
        $developer?->delete();
    }

    public function getDeveloperById(string $id): Model|Collection|array|null
    {
        return $this::find($id);
    }
}
