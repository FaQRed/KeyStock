<?php

namespace promoCode_repo;

use PromoCode;

interface PromoCodeRepositoryInterface
{
    public function getPromoCode($code);
    public function create(PromoCode $promoCode);
    public function getAllPromoCodes();
    public function getPromoCodeById($id);
    public function update(PromoCode $promoCode);
    public function delete($id);
}