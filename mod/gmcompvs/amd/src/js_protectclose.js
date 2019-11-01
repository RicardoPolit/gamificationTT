define([], function() {

    return {
        init: function() {
            // Put whatever you like here. $ is available
            // to you as normal.
            window.addEventListener('beforeunload', function (e) {
                e.preventDefault();
                e.returnValue = '';
            });
        }
    };
});