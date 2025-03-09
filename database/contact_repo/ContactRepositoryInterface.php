<?php

interface ContactRepositoryInterface{
    public function create(Contact $contact);
    public function read($id);
    public function readByUserId($userId);
    public function update(Contact $contact);
    public function delete($id);

}
