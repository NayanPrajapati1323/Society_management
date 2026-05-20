<?php

namespace App\Repositories;

use App\Models\Visitor;

class VisitorRepository
{
    public function find($id)
    {
        return Visitor::find($id);
    }

    public function findByMobile($mobile)
    {
        return Visitor::where('mobile', $mobile)->first();
    }

    public function create(array $data)
    {
        return Visitor::create($data);
    }

    public function update($id, array $data)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update($data);
        return $visitor;
    }

    public function search($query)
    {
        return Visitor::where('name', 'like', "%{$query}%")
                      ->orWhere('mobile', 'like', "%{$query}%")
                      ->orWhere('vehicle_number', 'like', "%{$query}%")
                      ->get();
    }
}
