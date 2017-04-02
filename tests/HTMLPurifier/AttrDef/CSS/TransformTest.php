<?php

class HTMLPurifier_AttrDef_CSS_TransformTest extends HTMLPurifier_AttrDefHarness
{

    public function test()
    {
        $this->def = new HTMLPurifier_AttrDef_CSS_Transform();

        $this->assertDef('scale( 2, 2)', 'scale(2,2)'); // integer, integer
        $this->assertDef('scale(2.3, 2)', 'scale(2.3,2)');  // float, intger
        $this->assertDef('scale( 2.3, 2.3)', 'scale(2.3,2.3)'); // float, float
        $this->assertDef('scale( 2, 2.3)', 'scale(2,2.3)'); // integer, float
        $this->assertDef('scale( 110, 2)', 'scale(100,2)'); // max value 100
        $this->assertDef('scale( 2, 110)', 'scale(2,100)'); // max value 100
        $this->assertDef('scale( 100.1, 2)', 'scale(100,2)'); // max value 100.0
        $this->assertDef('scale(2, 100.1)', 'scale(2,100)'); // max value 100.0

        $this->assertDef('scale(2, 2)', 'scale(2,2)'); // rm spaces
        $this->assertDef('scale(2,2)', 'scale(2,2)');
        
        // Invalid Arguments
        $this->assertDef('scale2,2)', false);
        $this->assertDef('scale(2,a)', false);
        $this->assertDef('scale(2,a)', false);
        $this->assertDef('scale(2,2,2)', false);

    }

}

// vim: et sw=4 sts=4
