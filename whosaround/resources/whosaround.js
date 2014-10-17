(function ($) {

    Whosaround = function () {}
    this.publicKey = null;
    this.channelName = null;
    this.onlineCount = 0;

    Whosaround.setPublicKey = function () {
        this.publicKey = arguments[0];

        return this.publicKey;
    }

    Whosaround.setChannelName = function () {
        this.channelName = arguments[0];
        
        return this.channelName;
    }

    Whosaround.getFieldName = function () {
        return this.fieldname;
    }

    Whosaround.setOnlineCount = function () {
        var count = arguments[0];

        // Check if count is set, or if it's larger than 0
        if (!count || count < 0) count = 0;

        this.onlineCount = count;

        // Set count
        $('#js-whosaround-count').text(this.onlineCount);

        // Hide/show table if count is 0
        (count > 0) ? $('#js-whosaround-table').slideDown(250) : $('#js-whosaround-table').slideUp(250);
    }

    Whosaround.decreaseOnlineCount = function () {
        this.onlineCount--;
        Whosaround.setOnlineCount(this.onlineCount);
    }

    Whosaround.increaseOnlineCount = function () {
        this.onlineCount++;
        Whosaround.setOnlineCount(this.onlineCount);
    }

    Whosaround.addMember = function(member){
            // Default to 'No name'
            var name = 'No name';

            // Set name if we got any
            if (member.info && member.info.name) name = member.info.name;
            
            // Create row            
            var $row = $('<tr />', { id: "member_" + member.id });

            // Set data to be added
            var data = [ name, member.info.ip, member.info.username ];

            // Add every data column
            $(data).each(function(index, item)
            {
                var $td = $('<td />', { text: item }).attr('scope', 'row');
                $row.append($td);
            });

            // Append row to table
            $("#whosaround-list").append( $row );

            // Increase online count            
            Whosaround.increaseOnlineCount();
        }
        
    Whosaround.removeMember = function(member){
        $("#member_"+ member.id).remove();
        Whosaround.decreaseOnlineCount();
    }

    Whosaround.init = function () {
        Pusher.channel_auth_endpoint = Craft.getActionUrl('whosAround/authenticate');
        
        Pusher.log = function(message) {
          if (window.console && window.console.log) {
            window.console.log(message);
          }
        };

        Whosaround.setOnlineCount();

        var pusher = new Pusher(this.publicKey);
        var channel = pusher.subscribe(this.channelName);
        
        channel.bind('pusher:subscription_succeeded', function(members) {
          $('#whosaround-list').empty();
            members.each(function(member) {
                Whosaround.addMember(member);
            });

            Whosaround.setOnlineCount(members.count);
        });
        
        channel.bind('pusher:member_added', function(member) {
            console.log(member);
            
          Whosaround.addMember(member);
        });

        channel.bind('pusher:member_removed', function(member) {
          Whosaround.removeMember(member);
        });
    }

})(jQuery);

Whosaround.setPublicKey(window.whosAroundSettings.publicKey);
Whosaround.setChannelName(window.whosAroundSettings.channelName);
Whosaround.init();