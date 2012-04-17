Readme
======

> A PHP library that contains a number of classes to allow the use
> of the [API provided by encoding.com] (http://encoding.com/api).  The library comes in two 
> parts: 

    *Encoder (library/EncoderApi.php) 
    *Responder (library/Responder.php)


Encoder
-------

> The encoder simply allows the user to specify the source video and the 
> destination video along with the required format. The API will respond 
> and if all is well, will provide a media id which can be used as a 
> local reference to store updates later on.
> 
> The encoder reads configuration data required to use the API including the
> userid and password. This information is provided by encoding.com once you
> have an account. 

Responder
---------

> When making the initial request with the responder, there is an option
> to specify an notify url. When the request has completed or when there
> is an update from encoding.com, they will hit the url you have specified
> with update information. This is handled by the Responder class.

> Currently this only provides information about AddMedia requests.

> The responder will update the local entry based on the media id saved 
> beforehand from the encoder.  




