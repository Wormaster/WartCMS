/**
* Neo v1.0.0 by yokCreative
* Copyright 2013  
* 
*/

// Sidebar Toggle
 $("#menu-toggle").click(function(e) {
      e.preventDefault();
     $("#wrapper").toggleClass("sidebar-active", 200, "easeOutBounce");
 });
        