 function getUrlParam(name) {
    var url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}


function ChangeFrame(id) {
    var dvPlayer = document.getElementById("dvPlayer");

    if (dvPlayer != null) {
        var iframe = "";
        if (id == 1)
        {
            iframe = '<iframe id="fp_embed_player" src="http://eyukti.com/tv/sporttv.php?key=HDMI10" marginwidth="0" marginheight="0" frameborder="0" width="100%" height="100%" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
        }
        else if (id == 2)
        {
            iframe = '<iframe id="fp_embed_player" src="http://eyukti.com/tv/sporttv.php?key=HDMI11" marginwidth="0" marginheight="0" frameborder="0" width="100%" height="100%" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
        }
        else if (id == 3)
        {
            iframe = '<iframe id="fp_embed_player" src="http://139.162.205.103/livetv/player3.html" marginwidth="0" marginheight="0" frameborder="0" width="100%" height="100%" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
        }
        else if (id == 4)
        {
            iframe = '<iframe id="fp_embed_player" src="http://139.162.205.103/livetv/player4.html" marginwidth="0" marginheight="0" frameborder="0" width="100%" height="100%" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
        }
        else if (id == 5)
        {
            iframe = '<iframe id="fp_embed_player" src="http://139.162.205.103/livetv/Tennis.html" marginwidth="0" marginheight="0" frameborder="0" width="100%" height="100%" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';

        }
        dvPlayer.innerHTML = iframe;
    }
}


function ChangeFrameN2() {
    var dvPlayerN2 = document.getElementById("dvPlayernew2");
	//var url = window.location.href;
 

var MatchId = getUrlParam("MatchId")
 
	// alert(MatchId);	 
    if (dvPlayerN2 != null) {
        iframe = '<iframe id="fp_embed_player" src="https://new.apple365.bet/tvsetting/showTvFeedApi/'+MatchId+'/45.127.45.253" marginwidth="0" marginheight="0" frameborder="0" width="100%" height="197" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
       
        
        dvPlayerN2.innerHTML = iframe;
    }
}




function ChangeFrameN() {
    var dvPlayerN = document.getElementById("dvPlayernew");
	//var url = window.location.href;
 

var MatchId = getUrlParam("MatchId")
 
	// alert(MatchId);	 
    if (dvPlayerN != null) {
        iframe = '<iframe id="fp_embed_player" src="https://videoplayer.betfair.com/GetPlayer.do?tr=1&eID='+MatchId+'&allowPopup=true&contentType=viz&statsToggle=hide&contentOnly=true" marginwidth="0" marginheight="0" frameborder="0" width="100%" height="197" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
       
        
        dvPlayerN.innerHTML = iframe;
    }
}




 
