<?php

namespace App\Core;

use App\Core\Exceptions\HttpException;
use Psr\Http\Message\ServerRequestInterface as Request;

class Validator
{
    protected $ruleset = [];
    protected $request;

    function __construct(Request $request, $ruleset = [])
    {
        $this->request = $request;
        $this->ruleset = $ruleset;
    }

    public function validate()
    {
        $params = $this->request->getParsedBody();
        foreach($this->ruleset as $param => $rules) {
            foreach(explode('|', str_replace(' ', '', strtolower($rules))) as $rule) {
                switch ($rule) {
                    case 'required':
                        if(!$this->checkIsRequired($param)) throw new HttpException(400, $param . ' is required');
                        break;
                    default:
                        break;
                }
            }
        }
    }

    private function checkIsRequired($param) {
        $params = $this->request->getParsedBody();
        return array_key_exists($param, $params);
    }
}
