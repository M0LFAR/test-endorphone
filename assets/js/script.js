document.addEventListener('DOMContentLoaded', function(){
    var input = document.querySelectorAll('input[type=checkbox]');
    brendEvent.init(input);
});


brendEvent ={
  'feed':'',
  'hrefNew':[],
   'idsBrends':[],
  'init': function (arrayInput) {
      arrayInput.forEach(this.changeForElements);
  },

  'changeForElements':function (element) {
          brendEvent.idsBrends[element.value]=element.name;
          
          if(element.checked){
             brendEvent.hrefNew.push(element.name);  
          }

      element.addEventListener('change', function (event) {
          var id = this.value;
          if (this.checked) {
             brendEvent.requestDeviceByIdBrend(id);
             brendEvent.putFeedById(id);
              brendEvent.hrefNew.push(this.name);  
          } else {
              brendEvent.removeItemsMyIdBrend(id);
              brendEvent.hrefNew.remove(this.name);
          }

          brendEvent.reloadHref();
      });
  },

 'requestDeviceByIdBrend': function (id) {
     var xhr = new XMLHttpRequest();
     var name =brendEvent.idsBrends[id];
     xhr.open('GET', 'index.php?brands='+name, false);
     xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
     xhr.send();

     if (xhr.status != 200) {
         alert( xhr.status + ': ' + xhr.statusText );
     } else {
         var jsonData = JSON.parse(xhr.responseText );
         jsonData[id].forEach(brendEvent.compliteElements);
     }
 },

 'removeItemsMyIdBrend': function (id) {
     document.getElementById(id).remove();
 },

 'compliteElements': function (elementArray) {
     brendEvent.feed += '<li>'+elementArray.name+'</li>';
 },

 'putFeedById': function (id) {
     var iterationUl;
     var  putContent =  document.createElement('ul');
     putContent.setAttribute('id', id);
     putContent.innerHTML = brendEvent.feed;
     for (var idBrand in brendEvent.idsBrends) {
         iterationUl = document.getElementById(idBrand);
         if(idBrand>id  && iterationUl!==null){
            break;
         }
     }

     if(iterationUl=='undefined' || iterationUl===null)
      document.getElementById('content').appendChild(putContent);
     else
        iterationUl.parentNode.insertBefore(putContent, iterationUl);

     brendEvent.feed='';
 },

 'reloadHref': function(){
    window.history.replaceState('{}', "test", "?brands="+brendEvent.hrefNew.join('+'));
 } 

};


Array.prototype.remove = function(value) {
    var idx = this.indexOf(value);
    if (idx != -1) {
        return this.splice(idx, 1);
    }
    return false;
}