<?php 

if(!class_exists("BC_Options")){
    require_once(BC_LOCATION . "/utils/BC_Currency_Orientation.php");
    class BC_Options implements \JsonSerializable{
        public ?string $heading = "";
        public ?string $currency = "";
        public ?BC_Currency_Orientation $currencyOrientation;
        public object $colors;
        public object $input;
        public object $output;

        public function __construct(string $jsonliteral) {
            //echo "literal: " . $jsonliteral . "<br><br>";
            $object = json_decode($jsonliteral, false, 512, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_THROW_ON_ERROR);
            //echo "jsonliteral converted: <br>";
            //var_dump($object);
            //echo json_last_error();
            //echo json_last_error_msg();
            $this->heading = (isset($object->heading) ? $object->heading : null);
            $this->currency = (isset($object->currency) ? $object->currency : null);
            $this->currencyOrientation = (isset($object->currencyOrientation) ? $object->currencyOrientation : null);
            $this->colors = new stdClass();
            $this->colors->primary = (isset($object->colors->primary) ? $object->colors->primary : null);
            $this->colors->secondary = (isset($object->colors->secondary) ? $object->colors->secondary : null);
            $this->colors->third = (isset($object->colors->third) ? $object->colors->third : null);
            $this->colors->background = (isset($object->colors->background) ? $object->colors->background : null);
            $this->colors->foreground = (isset($object->colors->foreground) ? $object->colors->foreground : null);
            $this->colors->foregroundAlternative = (isset($object->colors->foregroundAlternative) ? $object->colors->foregroundAlternative : null);
            $this->input = new stdClass();
            $this->input->monthlyRevenue = new stdClass();
            $this->input->monthlyRevenue->heading = (isset($object->input->monthlyRevenue->heading) ? $object->input->monthlyRevenue->heading : null);
            $this->input->monthlyRevenue->tooltip = new stdClass();
            $this->input->monthlyRevenue->tooltip->icon = (isset($object->input->monthlyRevenue->tooltip->icon) ? $object->input->monthlyRevenue->tooltip->icon : null);
            $this->input->monthlyRevenue->tooltip->text = (isset($object->input->monthlyRevenue->tooltip->text) ? $object->input->monthlyRevenue->tooltip->text : null);
            $this->input->monthlyRevenue->tooltip->ariaLabel = (isset($object->input->monthlyRevenue->tooltip->ariaLabel) ? $object->input->monthlyRevenue->tooltip->ariaLabel : null);
            $this->input->monthlyRevenue->slider = new stdClass();
            $this->input->monthlyRevenue->slider->min = (isset($object->input->monthlyRevenue->slider->min) ? $object->input->monthlyRevenue->slider->min : null);
            $this->input->monthlyRevenue->slider->max = (isset($object->input->monthlyRevenue->slider->max) ? $object->input->monthlyRevenue->slider->max : null);
            $this->input->monthlyRevenue->slider->step = (isset($object->input->monthlyRevenue->slider->step) ? $object->input->monthlyRevenue->slider->step : null);

            $this->input->industry = new stdClass();
            $this->input->industry->heading = (isset($object->input->industry->heading) ? $object->input->industry->heading : null);
            $this->input->industry->tooltip = new stdClass();
            $this->input->industry->tooltip->icon = (isset($object->input->industry->tooltip->icon) ? $object->input->industry->tooltip->icon : null);
            $this->input->industry->tooltip->text = (isset($object->input->industry->tooltip->text) ? $object->input->industry->tooltip->text : null);
            $this->input->industry->tooltip->ariaLabel = (isset($object->input->industry->tooltip->ariaLabel) ? $object->input->industry->tooltip->ariaLabel : null);
            $this->input->industry->industries = [];
            /*$industry1 = new stdClass();
            $industry1->text = "Industry1";
            $industry1->percentage = 30;
            $industry2 = new stdClass();
            $industry2->text = "Industry2";
            $industry2->percentage = 20;*/
            foreach($object->input->industry->industries as $industry){
                array_push($this->input->industry->industries, $industry);
            }
            //array_push($this->input->industry->industries, $industry1);
            //array_push($this->input->industry->industries, $industry2);

            $this->input->growth = new stdClass();
            $this->input->growth->heading = (isset($object->input->growth->heading) ? $object->input->growth->heading : null);
            $this->input->growth->tooltip = new stdClass();
            $this->input->growth->tooltip->icon = (isset($object->input->growth->tooltip->icon) ? $object->input->growth->tooltip->icon : null);
            $this->input->growth->tooltip->text = (isset($object->input->growth->tooltip->text) ? $object->input->growth->tooltip->text : null);
            $this->input->growth->tooltip->ariaLabel = (isset($object->input->growth->tooltip->ariaLabel) ? $object->input->growth->tooltip->ariaLabel : null);
            $this->input->growth->buttons = [];
            /*$button1 = new stdClass();
            $button1->text = "Conservative";
            $button1->multiplier = 1;
            $button2 = new stdClass();
            $button2->text = "Moderate";
            $button2->multiplier = 1.5;
            array_push($this->input->growth->buttons, $button1);
            array_push($this->input->growth->buttons, $button2);*/
            foreach($object->input->growth->buttons as $button){
                array_push($this->input->growth->buttons, $button);
            }
            $this->output = new stdClass();
            $this->output->heading = new stdClass();
            $this->output->heading->text = (isset($object->output->heading->text) ? $object->output->heading->text : null);
            $this->output->heading->colored = (isset($object->output->heading->colored) ? $object->output->heading->colored : null);
            $this->output->revenuePercentageLabel = (isset($object->output->revenuePercentageLabel) ? $object->output->revenuePercentageLabel : null);
            $this->output->perMonthLabel = (isset($object->output->perMonthLabel) ? $object->output->perMonthLabel : null);
            $this->output->perMonthTooltip = new stdClass();
            $this->output->perMonthTooltip->icon = (isset($object->output->perMonthTooltip->icon) ? $object->output->perMonthTooltip->icon : null);
            $this->output->perMonthTooltip->text = (isset($object->output->perMonthTooltip->text) ? $object->output->perMonthTooltip->text : null);
            $this->output->perMonthTooltip->ariaLabel = (isset($object->output->perMonthTooltip->ariaLabel) ? $object->output->perMonthTooltip->ariaLabel : null);
            $this->output->breakdown = new stdClass();
            $this->output->breakdown->heading = (isset($object->output->breakdown->heading) ? $object->output->breakdown->heading : null);
            $this->output->breakdown->openIcon = (isset($object->output->breakdown->openIcon) ? $object->output->breakdown->openIcon : null);
            $this->output->breakdown->listIcon = (isset($object->output->breakdown->listIcon) ? $object->output->breakdown->listIcon : null);
            $this->output->breakdown->hint = (isset($object->output->breakdown->hint) ? $object->output->breakdown->hint : null);
            $this->output->breakdown->breakdowns = [];
            if(isset($object->output->breakdown->breakdowns)){
                foreach($object->output->breakdown->breakdowns as $breakdown){
                    array_push($this->output->breakdown->breakdowns, $breakdown);
                }
            }
            
            $this->output->button = new stdClass();
            $this->output->button->text = (isset($object->output->button->text) ? $object->output->button->text : null);
            $this->output->button->href = (isset($object->output->button->href) ? $object->output->button->href : null);
            $this->output->endHint = (isset($object->output->endHint) ? $object->output->endHint : null);
            //var_dump(json_encode($this));
            //var_dump(get_object_vars($this));
            
        }
        public function jsonSerialize(): mixed
        {
            $vars = get_object_vars($this);

            return $vars;
        }
    }
}