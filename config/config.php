<?php

return array(

  'enabled' => true,

  'password' => '',

  /*
   * -----------------------------------------------------------------------------
   * Require password on selected environments
   * -----------------------------------------------------------------------------
   *
   * Profiler can be enabled by running: /_profiler/enable/{password} in browser.
   * Some environemts require password to be given before enabling.
   */

  'require_password' => array('production', 'prod'),

);
