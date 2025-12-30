<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Common;

class UniqueTranslationRule implements Rule
{
    protected $parameters;
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @param  mixed  $parameter
     *
     * @return void
     */
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {  //PGupta
        $attribute  = explode(".",$attribute);
        $mainTable  = explode("_",$this->parameters[0]);
        $this->attribute = $attribute[0];
        $DB = (new Common)->setTable($this->parameters[0])->newQuery();
        if(empty($this->parameters[1])){
            $exists = $DB->where($attribute[0],$value)->where('language_id',$attribute[1])
            ->whereNull('deleted_at')->first();
        }else{
            $exists = $DB->where($mainTable[0].'_id', '!=' , $this->parameters[1])
            ->where($attribute[0],$value)->where('language_id',$attribute[1])->whereNull('deleted_at')->first();
        }
        return $exists ? false :true ;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $field  = 'name';
        return __("validations.uniqueTranslation",[$field=>$this->attribute]);
    }
}
