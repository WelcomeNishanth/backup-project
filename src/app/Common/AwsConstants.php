<?php

namespace App\Common;

/**
 * This contains all the constants for the AWS SDK.
 *
 * @author Ajay Garga
 *
 */
interface AwsConstants {
    
    const VALIDATION = 'validation';
    
    const SCHEME = 'scheme';
    
    const HTTP_SCHEME = 'http';
    
    const CREDENTIALS = 'credentials';
    
    const REGION = 'region';
    
    const VERSION = 'version';
    
    const LATEST = 'latest';
    
    const CREDENTIALS_CACHE = 'credentials.cache';
    
    const BACKOFF_LOGGER = 'client.backoff.logger';
    
    const CREDENTIALS_CLIENT = 'credentials.client';
    
    const BUCKET = 'Bucket';
    
    const KEY = 'Key';
    
    const LASTMODIFIED = 'LastModified';
    
    const BODY = 'Body';
    
    const TOPIC_ARN = 'TopicArn';
    
    const MESSAGE = 'Message';

    const SUBJECT = 'Subject';

    const MESSAGE_ATTRIBUTES = 'MessageAttributes';
    
} 