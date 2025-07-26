<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Support\Collection;

class AccountService {

    public function __construct(AccountRepositoryInterface $AccountRepository){
        $this->AccountRepository = $AccountRepository;
    }


    
}