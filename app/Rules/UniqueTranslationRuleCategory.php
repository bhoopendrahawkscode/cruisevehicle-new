<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Common;
use App\Models\CategoryTranslation;
use DB;
class UniqueTranslationRuleCategory implements Rule
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
    {


        //PGupta
        $attribute  = explode(".",$attribute);
        $mainTable  = explode("_",$this->parameters[0]);

        $this->attribute = $attribute[0];
        $query 		=  		new CategoryTranslation();
        $query 		= 		$query->newQuery();
        $query->where($attribute[0],$value)
        ->where('language_id',$attribute[1])
        ->whereNull('category_translations.deleted_at');
        if(isset($this->parameters[2])){ // to handle parent id for category and faq module
            $query->where('parent_id', '=' , $this->parameters[2]);
        }
        if(!empty($this->parameters[1])){
            $query->where($mainTable[0].'_id', '!=' , $this->parameters[1]);
        }

        $exists = $query->select('*')
        ->rightJoin('categories', 'category_translations.category_id', '=', 'categories.id')->first();

        return $exists ? false :true ;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __("validations.uniqueTranslation",[$this->attribute=>$this->attribute]);
    }
}

