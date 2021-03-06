<?php

namespace App\Domains\Doctor;

use App\Domains\Doctor\Contracts\DoctorRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DbDoctorRepository implements DoctorRepositoryInterface
{
    /**
     * @var Doctor
     */
    private $model;

    /**
     * @var int
     */
    private $perPage = 25;

    /**
     * @param Doctor $model
     */
    public function __construct(Doctor $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllPluckedUp()
    {
        return $this
            ->model
            ->newQuery()
            ->select([
                'doctors.name',
                'doctors.id'
            ])
            ->whereNull('deleted_at')
            ->get()
            ->sortBy('name')
            ->pluck('name', 'id');
    }

    /**
     * Get all doctors.
     *
     * @param string $sortBy
     * @param string $orientation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll($sortBy = 'name', $orientation = 'asc')
    {
        return $this
            ->model
            ->newQuery()
            ->select([
                'doctors.id',
                'doctors.name',
                'doctors.email',
                'doctors.cpf',
            ])
            ->whereNull('deleted_at')
            ->orderBy($sortBy, $orientation)
            ->paginate($this->perPage);
    }

    /**
     * Create a doctor.
     *
     * @param $name
     * @param $email
     * @param $cpf
     * @param $crm
     * @param null $specialty
     * @return bool|Doctor
     */
    public function createNewDoctor($name, $email, $cpf, $crm, $specialty = null)
    {
        /** @var  Doctor $doctor */
        $doctor = $this->model;

        $doctor->name = $name;
        $doctor->email = $email;
        $doctor->cpf = $cpf;
        $doctor->crm = $crm;
        $doctor->specialty = $specialty;

        if (!$doctor->save()) {
            return false;
        }

        return $doctor;
    }

    /**
     * Find a doctor by id.
     *
     * @param $id
     * @return mixed
     */
    public function findDoctorById($id)
    {
        $doctor = $this
            ->model
            ->whereRaw('(doctors.deleted_at is null or doctors.deleted_at = "")')
            ->whereId($id)
            ->first();

        if (! $doctor) {
            throw new ModelNotFoundException("Doctor with id: ${id} not found.");
        }

        return $doctor;
    }

    /**
     * Update a doctor.
     *
     * @param $id
     * @param $name
     * @param $email
     * @param $cpf
     * @param $crm
     * @param null $specialty
     * @return mixed
     */
    public function updateDoctorById($id, $name, $email, $cpf, $crm, $specialty = null)
    {
        $doctor = $this->findDoctorById($id);

        $doctor->name = $name;
        $doctor->email = $email;
        $doctor->cpf = $cpf;
        $doctor->crm = $crm;
        $doctor->specialty = $specialty;

        if (!$doctor->save()) {
            return false;
        }

        return $doctor;
    }

    /**
     * Delete a doctor.
     *
     * @param $id
     * @return bool
     */
    public function deleteDoctorById($id)
    {
        if ($model = $this->findDoctorById($id)) {
            $model->deleted_at = now()->toDateTimeString();
            $model->save();
            return true;
        }

        return false;
    }
}
