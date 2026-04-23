<?php

namespace App\Repositories;

use App\Models\Society;

class SocietyRepository
{
    public function find($id)
    {
        return Society::find($id);
    }

    public function getAllActive()
    {
        return Society::where('is_active', true)->get();
    }

    public function getByCity($city)
    {
        return Society::where('city', $city)->where('is_active', true)->get();
    }

    public function create(array $data)
    {
        return Society::create($data);
    }

    public function update($id, array $data)
    {
        $society = Society::findOrFail($id);
        $society->update($data);
        return $society;
    }
}
