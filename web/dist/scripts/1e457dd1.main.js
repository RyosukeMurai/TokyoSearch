$(function(){$.fn.extend({render:function(a,b,c){return $.when($.ajax({type:"get",url:a,async:c||!1,success:$.proxy(function(a){$(this).append($.templates(a).render(b))},this)}))}});var a=function(a){var b=document.domain.match(/localhost|vagrant/i)?a.domainOfDev:a.domainOfWeb,c=function(c){return a.protocol+"://"+b+"/"+c},d=function(a,b,c){$.ajax({type:"get",url:a,data:b,async:!0,dataType:"jsonp",success:c,error:function(a){console.log(a)}})};return{domain:b,affiliate:{amazonAffiliateFile:"./external/amazon.html"},getMixedPosts:function(a,b){d(c("api/post/posts-by-location"),a,b)},getPlaces:function(a,b){d(c("api/place"),a,b)}}}({domainOfWeb:"tokyosearch.herokuapp.com",domainOfDev:"tokyosearch.vagrant",protocol:"http"});$.extend({tokyo:{config:{},urlManeger:a,initApps:function(){$.tokyo.initMatelialize(),$.tokyo.addEventListeners(),$.tokyo.initGoogleMap(),$.tokyo.renderAffiliate()},initMatelialize:function(){$(".button-collapse").sideNav(),$(".parallax").parallax()},addEventListeners:function(){$("#start").on("click",function(){$.tokyo.popupPlaces()})},renderAffiliate:function(){$(".affiliate").render($.tokyo.urlManeger.affiliate.amazonAffiliateFile)},initGoogleMap:function(){$.tokyo.getLocation(function(a){var b=new google.maps.Map($(".googlemap").get(0),{center:new google.maps.LatLng(a.coords.latitude,a.coords.longitude),zoom:10,region:"jp"});google.maps.event.addListener(b,"click",function(a){$.tokyo.renderPosts({latitude:a.latLng.lat(),longitude:a.latLng.lng()})}),$.tokyo.map=b})},getLocation:function(a){navigator.geolocation.getCurrentPosition(function(b){a(b)},function(){},{enableHighAccuracy:!0,timeout:6e3,maximumAge:6e5})},renderPosts:function(a){$.tokyo.urlManeger.getMixedPosts(a,function(a){$(".content").children().remove(),$(".content").render("./templates/content.html",a)})},popupPlaces:function(a){$(".modal_wrap").children().remove();var b=function(a){$.tokyo.urlManeger.getPlaces(a,function(a){$(".modal_wrap").render("./templates/modal.html",a),$(".modal .collection-item").on("click",function(){$.tokyo.renderPosts($(this).attr("data-id")),$(".modal").closeModal()}),$(".modal").openModal()})};return a?b(a):void $.tokyo.getLocation(function(a){b(a.coords)})}}}),$.tokyo.initApps()});