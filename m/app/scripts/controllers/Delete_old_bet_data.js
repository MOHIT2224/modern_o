
app.controller("Delete_old_bet_data", ["$scope", "$http", function(e, r) { 
		
		e.submitForm=function() {
      
        r.get(BASE_URL+"Deletedata/delete_all_bet").success(function(r, s, a, n) {
           if(r.error){
e.deleteDta=r.data;
e.message=r.message;
console.log(e.deleteDta);
}else{
e.message=r.message;
}
        
    })
    }
    
}

]);
