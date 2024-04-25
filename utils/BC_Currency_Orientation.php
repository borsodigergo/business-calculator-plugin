<?php 

/**
 * Enum to store the position of the currency where it is displayed.
 */
enum BC_Currency_Orientation: string{
    /**
     * Display the currency after the numeric values
     */
    case AFTER_TEXT = "AFTER_TEXT";

    /**
     * Display the currency before the numeric values
     */
    case BEFORE_TEXT = "BEFORE_TEXT";
}