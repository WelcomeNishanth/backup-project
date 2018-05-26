<?php

namespace App\Common;

/**
 * This contains all the constants for the AWS SDK.
 *
 * @author Ajay Garga
 *
 */
interface AppConstants {
    
    const DEFAULT_MODE = 0775;
    
    const LOG_DIRECTORY = 'log';
    
    const ENV_ACCESS_KEY = "AWS_ACCESS_KEY";
	
    const ENV_SECRET_KEY = "AWS_SECRET_KEY";
    
    const GATEWAY = 'Gateway';
    
    const DATA_TYPE = 'DataType';
    
    const STRING_VALUE = 'StringValue';
    
    const STRING = 'String';
    
    const DATA = 'data';
    
    const OBJECT_KEY = 'objectKey';
    
    const ID = 'Id';
    
    const MSGKEY = 'ObjectKey';
    
    const JSON_VERSION = 'JsonVersion';
    
    const LAST_MODIFIED = 'LastModified';
    
    const ACTION = 'Action';
    
    const KIND = 'Type';
    
    const SOURCE = 'Source';
    
    const ACTION_UPDATE = 'Update';
    
    const ACTION_CREATE = 'Create';
    
    const ACTION_DELETE = 'Delete';
    
  }
