// Define the module for our AngularJS application.
(function(){

    var pseudoFactory = (function() {

        // I prepare the request data for the form post.
        function transformRequest( data, getHeaders ) {

            var headers = getHeaders();

            headers[ "Content-type" ] = "application/x-www-form-urlencoded; charset=utf-8";

            return( serializeData( data ) );

        }


        // Return the factory value.
        return( transformRequest );


        // ---
        // PRVIATE METHODS.
        // ---


        // I serialize the given Object into a key-value pair string. This
        // method expects an object and will default to the toString() method.
        // --
        // NOTE: This is an atered version of the jQuery.param() method which
        // will serialize a data collection for Form posting.
        // --
        // https://github.com/jquery/jquery/blob/master/src/serialize.js#L45
        function serializeData( data ) {

            // If this is not an object, defer to native stringification.
            if ( ! angular.isObject( data ) ) {

                return( ( data == null ) ? "" : data.toString() );

            }

            var buffer = [];

            // Serialize each key in the object.
            for ( var name in data ) {

                if ( ! data.hasOwnProperty( name ) ) {

                    continue;

                }

                var value = data[ name ];

                buffer.push(
                    encodeURIComponent( name ) +
                    "=" +
                    encodeURIComponent( ( value == null ) ? "" : value )
                );

            }

            // Serialize the buffer and clean it up for transportation.
            var source = buffer
                    .join( "&" )
                    .replace( /%20/g, "+" )
                ;
            console.log("source",source);
            return( source );

        }

    })();

angular.module( "NetGear", [] )
    /* .config(['$httpProvider', function ($httpProvider) {
        //$httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
        //$httpProvider.defaults.headers[ "Content-type" ] = "application/x-www-form-urlencoded; charset=utf-8";
        console.log("pseudoFactory",pseudoFactory);
        $httpProvider.defaults.transformRequest = pseudoFactory;

    }]);*/
    .factory('ajaxAction',[function(){
        return function(action){
            return ajaxurl + "?action=" + action;
        }
    }]);
})();