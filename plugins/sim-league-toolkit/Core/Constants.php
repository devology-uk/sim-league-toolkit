<?php

  namespace SLTK\Core;

  class Constants {
    public final const string ACTION_ADD = 'add';
    public final const string ACTION_DELETE = 'delete';
    public final const string ACTION_EDIT = 'edit';
    public final const int DEFAULT_ID = -1;
    public final const string EDIT_USER_PERMISSION = 'edit_user';
    public final const string MANAGE_OPTIONS_PERMISSION = 'manage_options';
    public final const string PLUGIN_ROOT_DIR = SLTK_PLUGIN_DIR;
    public final const string PLUGIN_ROOT_URL = SLTK_PLUGIN_ROOT_URL;
    public final const string STANDARD_DATE_DISPLAY_FORMAT = 'l, j M Y';
    public final const string STANDARD_DATE_FORMAT = 'Y-m-d';
    public final const string STANDARD_DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    public final const string STANDARD_TIME_FORMAT = 'G:i:s';
    public final const string STANDARD_TIME_OF_DAY_FORMAT = 'G:i';
    public final const string WORDPRESS_NONCE_NAME = '_wpnonce';
    public final const string WORDPRESS_UPDATED_KEY = 'updated';
  }