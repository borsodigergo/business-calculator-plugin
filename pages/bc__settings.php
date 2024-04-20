<?php


if (!class_exists("BC__Settings")) {
    require_once(BC_LOCATION . "/utils/BC_Options.php");
    require_once(BC_LOCATION . "/utils/BC_Data.php");

    $saveStatus = null;
    if(isset($_POST["bc__save"])){
        /*if(BC_Data::saveOptions($_POST)){
            $saveStatus = "OK";
        }else{
            $saveStatus = "ERROR";
        }*/
        $options = BC_Data::saveOptions($_POST);
        if($options == null){
            echo "error <br>";
        }else{
            echo "success <br>";
        }
        //var_dump(BC_Data::saveOptions($_POST));
    }







    class BC__Settings
    {
        public static function display(BC_Options $options)
        {
            $orientation = ($options->currencyOrientation ? $options->currencyOrientation->value : "BEFORE_TEXT");
?>  
            <form method="post" class="container-fluid mt-5">
                <h1 class="mb-5">Business Calculator Settings</h1>
                <div class="row m-0">
                    <div class="col-6">
                        <h2 class="mb-5">General</h2>
                        <div class="mt-3 d-flex justify-content-between" style="max-width: 600px">
                            <label for="heading">Heading:</label>
                            <input type="text" name="heading" id="heading" value="<?= $options->heading ? $options->heading : "" ?>" placeholder="Calculate your Budget" style="width: 350px">
                        </div>
                        <div class="mt-3 d-flex justify-content-between" style="max-width: 600px">
                            <label for="currency">Currency:</label>
                            <input type="text" name="currency" id="currency" value="<?= $options->currency ? $options->currency : "" ?>" placeholder="$" style="width: 350px">
                        </div>
                        <div class="mt-3 d-flex justify-content-between" style="max-width: 600px">
                            Currency orientation:
                            <div>
                                <input type="radio" name="currencyOrientation" id="orientation_before" value="BEFORE_TEXT" <?= (($orientation == "BEFORE_TEXT") ? "checked" : "") ?>><label for="orientation_before">Before text</label><br>
                                <input type="radio" name="currencyOrientation" id="orientation_after" value="AFTER_TEXT" <?= (($orientation == "AFTER_TEXT") ? "checked" : "") ?>><label for="orientation_after">After text</label>
                            </div>
                        </div>
                        <div class="mt-5" style="max-width: 600px">
                            <hr>
                            <h2 class="mb-5">Inputs</h2>
                        </div>
                        <fieldset class="mt-3 border p-2" style="max-width: 600px">
                            <legend class="w-auto float-none">Slider Input</legend>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="input_monthlyRevenue_heading">Heading:</label>
                                <input type="text" name="input_monthlyRevenue_heading" id="input_monthlyRevenue_heading" value="<?= $options->input->monthlyRevenue->heading ? $options->input->monthlyRevenue->heading : "" ?>" placeholder="Monthly Revenue" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <details class="w-100">
                                    <summary>Tooltip</summary>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_monthlyRevenue_tooltip_icon">Icon:</label>
                                        <input type="text" name="input_monthlyRevenue_tooltip_icon" id="input_monthlyRevenue_tooltip_icon" value="<?= $options->input->monthlyRevenue->tooltip->icon ? $options->input->monthlyRevenue->tooltip->icon : "" ?>" placeholder="fa-light fa-circle-info" style="width: 350px">
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_monthlyRevenue_tooltip_text">Text:</label>
                                        <textarea name="input_monthlyRevenue_tooltip_text" id="input_monthlyRevenue_tooltip_text" style="width: 350px" placeholder="Your business' average gross monthly revenue - total brought in before any expenses."><?= $options->input->monthlyRevenue->tooltip->text ? $options->input->monthlyRevenue->tooltip->text : "" ?></textarea>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_monthlyRevenue_tooltip_ariaLabel">Aria Label:</label>
                                        <textarea name="input_monthlyRevenue_tooltip_ariaLabel" id="input_monthlyRevenue_tooltip_ariaLabel" style="width: 350px" placeholder="Open this tooltip to learn more about the Monthly Revenue"><?= $options->input->monthlyRevenue->tooltip->ariaLabel ? $options->input->monthlyRevenue->tooltip->ariaLabel : "" ?></textarea>
                                    </div>
                                </details>
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <details class="w-100">
                                    <summary>Slider</summary>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_monthlyRevenue_slider_min">Minimum:</label>
                                        <input type="number" name="input_monthlyRevenue_slider_min" id="input_monthlyRevenue_slider_min" value="<?= $options->input->monthlyRevenue->slider->min ? $options->input->monthlyRevenue->slider->min : "" ?>" placeholder="0" style="width: 350px">
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_monthlyRevenue_slider_max">Maximum:</label>
                                        <input type="number" name="input_monthlyRevenue_slider_max" id="input_monthlyRevenue_slider_max" value="<?= $options->input->monthlyRevenue->slider->max ? $options->input->monthlyRevenue->slider->max : "" ?>" placeholder="1000000" style="width: 350px">
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_monthlyRevenue_slider_step">Step:</label>
                                        <input type="number" name="input_monthlyRevenue_slider_step" id="input_monthlyRevenue_slider_step" value="<?= $options->input->monthlyRevenue->slider->step ? $options->input->monthlyRevenue->slider->step : "" ?>" placeholder="5000" style="width: 350px">
                                    </div>
                                </details>
                            </div>
                        </fieldset>
                        <fieldset class="mt-3 border p-2" style="max-width: 600px">
                            <legend class="w-auto float-none">Industries</legend>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="input_industry_heading">Heading:</label>
                                <input type="text" name="input_industry_heading" id="input_industry_heading" value="<?= $options->input->industry->heading ? $options->input->industry->heading : "" ?>" placeholder="Industry" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <details class="w-100">
                                    <summary>Tooltip</summary>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_industry_tooltip_icon">Icon:</label>
                                        <input type="text" name="input_industry_tooltip_icon" id="input_industry_tooltip_icon" value="<?= $options->input->industry->tooltip->icon ? $options->input->industry->tooltip->icon : "" ?>" placeholder="fa-light fa-circle-info" style="width: 350px">
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_industry_tooltip_text">Text:</label>
                                        <textarea name="input_industry_tooltip_text" id="input_industry_tooltip_text" style="width: 350px" placeholder="Select your business industry or closest match."><?= $options->input->industry->tooltip->text ? $options->input->industry->tooltip->text : "" ?></textarea>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_industry_tooltip_ariaLabel">Aria Label:</label>
                                        <textarea name="input_industry_tooltip_ariaLabel" id="input_industry_tooltip_ariaLabel" style="width: 350px" placeholder="Open this tooltip to learn more about the Industries"><?= $options->input->industry->tooltip->ariaLabel ? $options->input->industry->tooltip->ariaLabel : "" ?></textarea>
                                    </div>
                                </details>
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="input_industry_industries" style="max-width: 50%;">Industries: <span class="required"><b>*</b></span><br><small><i>One industry per row, format:<br>&lt;industry&gt;|&lt;percentage&gt;</i></small></label>
                                <textarea name="input_industry_industries" id="input_industry_industries" style="width: 350px; height: 500px;" placeholder="Industry Name|percentage"><?php if ($options->input->industry->industries) {
                                                                                                                                                                                            foreach ($options->input->industry->industries as $industry) {
                                                                                                                                                                                                echo trim($industry->text) . "|" . trim($industry->percentage) . "\n";
                                                                                                                                                                                            }
                                                                                                                                                                                        } ?></textarea>
                            </div>
                        </fieldset>
                        <fieldset class="mt-3 border p-2" style="max-width: 600px">
                            <legend class="w-auto float-none">Growth Goals</legend>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="input_growth_heading">Heading:</label>
                                <input type="text" name="input_growth_heading" id="input_growth_heading" value="<?= $options->input->growth->heading ? $options->input->growth->heading : "" ?>" placeholder="Growth Goals" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <details class="w-100">
                                    <summary>Tooltip</summary>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_growth_tooltip_icon">Icon:</label>
                                        <input type="text" name="input_growth_tooltip_icon" id="input_growth_tooltip_icon" value="<?= $options->input->growth->tooltip->icon ? $options->input->growth->tooltip->icon : "" ?>" placeholder="fa-light fa-circle-info" style="width: 350px">
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_industry_tooltip_text">Text:</label>
                                        <textarea name="input_industry_tooltip_text" id="input_industry_tooltip_text" style="width: 350px" placeholder="Conservative: a more cautious approach, aiming for steady and sustainable growth. <br>Moderate: balanced growth, combining stability with some expansion. <br>Aggressive: rapid growth and expansion, even if it involves higher risk."><?= $options->input->industry->tooltip->text ? $options->input->industry->tooltip->text : "" ?></textarea>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                        <label for="input_industry_tooltip_ariaLabel">Aria Label:</label>
                                        <textarea name="input_industry_tooltip_ariaLabel" id="input_industry_tooltip_ariaLabel" style="width: 350px" placeholder="Open this tooltip to learn more about the Growth Goals"><?= $options->input->industry->tooltip->ariaLabel ? $options->input->industry->tooltip->ariaLabel : "" ?></textarea>
                                    </div>
                                </details>
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="input_growth_buttons" style="max-width: 50%;">Buttons: <span class="required"><b>*</b></span><br><small><i>One button per row, format:<br>&lt;label&gt;|&lt;multiplier&gt;</i></small></label>
                                <textarea name="input_growth_buttons" id="input_growth_buttons" style="width: 350px; height: 150px;" placeholder="Button label|multiplier"><?php if ($options->input->growth->buttons) {
                                                                                                                                                                                foreach ($options->input->growth->buttons as $button) {
                                                                                                                                                                                    echo trim($button->text) . "|" . trim($button->multiplier) . "\n";
                                                                                                                                                                                }
                                                                                                                                                                            } ?></textarea>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-6">
                        <div class="mt-0" style="max-width: 600px">
                            <h2 class="mb-5">Output</h2>
                        </div>
                        <fieldset class="mt-3 border p-2" style="max-width: 600px">
                            <legend class="w-auto float-none">Heading</legend>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_heading_text">Text:</label>
                                <input type="text" name="output_heading_text" id="output_heading_text" value="<?= $options->output->heading->text ? $options->output->heading->text : "" ?>" placeholder="Your Recommended" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_heading_colored">Colored Text:</label>
                                <input type="text" name="output_heading_colored" id="output_heading_colored" value="<?= $options->output->heading->colored ? $options->output->heading->colored : "" ?>" placeholder="Marketing Spend" style="width: 350px">
                            </div>
                        </fieldset>
                        <div class="mt-4 d-flex justify-content-between" style="max-width: 600px">
                            <label for="output_revenuePercentageLabel">Percentage Label:</label>
                            <input type="text" name="output_revenuePercentageLabel" id="output_revenuePercentageLabel" value="<?= $options->output->revenuePercentageLabel ? $options->output->revenuePercentageLabel : "" ?>" placeholder="% of Revenue" style="width: 350px">
                        </div>
                        <div class="mt-3 d-flex justify-content-between" style="max-width: 600px">
                            <label for="output_perMonthLabel">Per Month Label:</label>
                            <input type="text" name="output_perMonthLabel" id="output_perMonthLabel" value="<?= $options->output->perMonthLabel ? $options->output->perMonthLabel : "" ?>" placeholder="per month" style="width: 350px">
                        </div>
                        <details class="mt-3" style="max-width: 600px">
                            <summary>Per Month Tooltip</summary>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 600px">
                                <label for="output_perMonthTooltip_icon">Icon:</label>
                                <input type="text" name="output_perMonthTooltip_icon" id="output_perMonthTooltip_icon" value="<?= $options->output->perMonthTooltip->icon ? $options->output->perMonthTooltip->icon : "" ?>" placeholder="fa-light fa-circle-info" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 600px">
                                <label for="output_perMonthTooltip_text">Text:</label>
                                <textarea name="output_perMonthTooltip_text" id="output_perMonthTooltip_text" style="width: 350px" placeholder="This marketing budget could be allocated to salaries, specialist agency support and marketing initiatives, campaigns, ad spends and related expenses."><?= $options->output->perMonthTooltip->text ? $options->output->perMonthTooltip->text : "" ?></textarea>
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 600px">
                                <label for="output_perMonthTooltip_ariaLabel">Aria Label:</label>
                                <textarea name="output_perMonthTooltip_ariaLabel" id="output_perMonthTooltip_ariaLabel" style="width: 350px" placeholder="Open this tooltip to learn more about the Monthly Budget"><?= $options->output->perMonthTooltip->ariaLabel ? $options->output->perMonthTooltip->ariaLabel : "" ?></textarea>
                            </div>
                        </details>
                        <fieldset class="mt-3 border p-2" style="max-width: 600px">
                            <legend class="w-auto float-none">Breakdown</legend>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_breakdown_heading">Heading:</label>
                                <input type="text" name="output_breakdown_heading" id="output_breakdown_heading" value="<?= $options->output->breakdown->heading ? $options->output->breakdown->heading : "" ?>" placeholder="Example Breakdown" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_breakdown_openIcon">Opening Icon:</label>
                                <input type="text" name="output_breakdown_openIcon" id="output_breakdown_openIcon" value="<?= $options->output->breakdown->openIcon ? $options->output->breakdown->openIcon : "" ?>" placeholder="fa-regular fa-chevron-down" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_breakdown_listIcon">List Icon (unicode):</label>
                                <input type="text" name="output_breakdown_listIcon" id="output_breakdown_listIcon" value="<?= $options->output->breakdown->listIcon ? $options->output->breakdown->listIcon : "" ?>" placeholder="f058" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_breakdown_hint">Hint:</label>
                                <textarea name="output_breakdown_hint" id="output_breakdown_hint" style="width: 350px" placeholder="Adapt your budget based on campaign results and business goals, experimenting with tailored strategies and channels."><?= $options->output->breakdown->hint ? $options->output->breakdown->hint : "" ?></textarea>
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_breakdown_breakdowns" style="max-width: 50%;">Breakdowns:<br><small><i>One breakdown per row, format:<br>&lt;breakdown&gt;|&lt;percentage&gt;</i></small></label>
                                <textarea name="output_breakdown_breakdowns" id="output_breakdown_breakdowns" style="width: 350px; height: 500px;" placeholder="Breakdown Name|percentage"><?php if ($options->output->breakdown->breakdowns) {
                                                                                                                                                                                                foreach ($options->output->breakdown->breakdowns as $breakdown) {
                                                                                                                                                                                                    echo trim($breakdown->text) . "|" . trim($breakdown->percentage) . "\n";
                                                                                                                                                                                                }
                                                                                                                                                                                            } ?></textarea>
                            </div>
                        </fieldset>
                        <fieldset class="mt-3 border p-2" style="max-width: 600px">
                            <legend class="w-auto float-none">Button</legend>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_button_text">Label:</label>
                                <input type="text" name="output_button_text" id="output_button_text" value="<?= $options->output->button->text ? $options->output->button->text : "" ?>" placeholder="See plans" style="width: 350px">
                            </div>
                            <div class="mt-3 d-flex justify-content-between mx-auto" style="max-width: 500px">
                                <label for="output_button_href">URL:</label>
                                <input type="text" name="output_button_href" id="output_button_href" value="<?= $options->output->button->href ? $options->output->button->href : "" ?>" placeholder="#" style="width: 350px">
                            </div>
                        </fieldset>
                        <div class="mt-4 d-flex justify-content-between" style="max-width: 600px">
                            <label for="output_endHint">Hint under button:</label>
                            <textarea name="output_endHint" id="output_endHint" style="width: 350px" placeholder="Unsure where to allocate your marketing spend? <a href='#'>Take the quiz</a>"><?= $options->output->endHint ? $options->output->endHint : "" ?></textarea>
                        </div>
                        <button name="bc__save" class="btn-primary btn mt-5">Save Calculator settings</button>
                    </div>
                    
                </div>
                
            </form>

<?php //TODO: settings save
        }
    }
}
