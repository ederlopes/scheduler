<?php

namespace App\Domains\Doctor\Contracts;

use App\Domains\Doctor\Doctor;

interface DoctorRepositoryInterface
{
    /**
     * @param Doctor $model
     */
    public function __construct(Doctor $model);

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllPluckedUp();

    /**
     * Get all doctors.
     *
     * @param string $sortBy
     * @param string $orientation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll($sortBy = 'name', $orientation = 'asc');

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
    public function createNewDoctor($name, $email, $cpf, $crm, $specialty = null);

    /**
     * Find a doctor by id.
     *
     * @param $id
     * @return mixed
     */
    public function findDoctorById($id);

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
    public function updateDoctorById($id, $name, $email, $cpf, $crm, $specialty = null);

    /**
     * Delete a doctor.
     *
     * @param $id
     * @return bool
     */
    public function deleteDoctorById($id);
}
