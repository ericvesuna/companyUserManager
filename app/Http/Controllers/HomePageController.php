<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;

class HomePageController extends Controller
{

    protected $company;
    protected $user;
    
    public function __construct(Company $company, User $user){
        $this->company = $company;
        $this->user = $user;
    }

    public function homePage(){
        $usersCount = $this->user->count();
        $companiesCount = $this->company->count();
        return view('homePage', compact('usersCount','companiesCount'));
    }
}
