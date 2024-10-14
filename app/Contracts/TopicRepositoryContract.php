<?php

namespace App\Contracts;

interface TopicRepositoryContract
{
    public function getAll(int $page, int $perPage);

    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
