<?php
class Cart {
    private $id;
    private $user_id;
    private $created_at;

    /**
     * @param $id
     * @param $user_id
     * @param $created_at
     */
    public function __construct($id, $user_id, $created_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }


}