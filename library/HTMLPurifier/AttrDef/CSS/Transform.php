<?php

/**
 * Validates Color as defined by CSS.
 */
class HTMLPurifier_AttrDef_CSS_Transform extends HTMLPurifier_AttrDef
{

    /**
     * @type HTMLPurifier_AttrDef_CSS_AlphaValue
     */
    protected $alpha;

    public function __construct()
    {
        $this->alpha = new HTMLPurifier_AttrDef_CSS_AlphaValue();
    }

    /**
     * Only allow scale() for the moment
     * @param string $transform
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return bool|string
     */
    public function validate($transform, $config, $context)
    {
        $transform = trim($transform);
        if ($transform === '') {
            return false;
        }

        if (preg_match('#(scale)\(#', $transform, $matches) === 1) {
            $length = strlen($transform);
            if (strpos($transform, ')') !== $length - 1) {
                return false;
            }

            // get used function : rgb, rgba, hsl or hsla
            $function = $matches[1];

            $parameters_size = 2;
        

            /*
             * Allowed types for values :
             * parameter_position => [type => max_value]
             * scale(int|float, int|float)
             */
            $allowed_types = array(
                1 => array('integer' => 100, 'float' => 100.0),
                2 => array('integer' => 100, 'float' => 100.0),
            );

            // Get values of scale()
            $values = trim(str_replace($function, '', $transform), ' ()');

            $parts = explode(',', $values);
            if (count($parts) !== $parameters_size) {
                return false;
            }

            $new_parts = array();
            $i = 0;

            foreach ($parts as $part) {
                $i++;
                $part = trim($part);

                if ($part === '') {
                    return false;
                }

                if (preg_match('/^\d+\.\d+$/',$part)) {
                    $current_type = 'float';
                } else if(is_numeric($part)){
                    $current_type = 'integer';
                } else {
                    return false;
                }

                if (!array_key_exists($current_type, $allowed_types[$i])) {
                    return false;
                }

                $max_value = $allowed_types[$i][$current_type];

                if ($current_type == 'integer') {
                    // Return value between range 0 -> $max_value
                    $new_parts[] = (int)max(min($part, $max_value), 0);
                } elseif ($current_type == 'float') {
                    // Check
                    $new_parts[] = (float)max(min($part, $max_value), 0);
                }
            }

            $new_values = implode(',', $new_parts);

            // Build function scale(val,val)
            return $function . '(' . $new_values . ')';
        }

        return false;
    }

}

// vim: et sw=4 sts=4
