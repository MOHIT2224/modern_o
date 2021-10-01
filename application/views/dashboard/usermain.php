<!-- Navigation -->
<userheader></userheader>
<!-- /.navbar-top-links -->
<!-- /.navbar-static-side -->
<div class="usercontent">
  <div class="row">
  
  <div ng-class="{'col-sm-12':!$root.rightBar,'col-sm-9':$root.rightBar}" class="desbord_user">
  
    <div class="content">
      <div ui-view="">

</div>

    </div>
    </div>
        
    
  <userrightbar ng-if="$root.rightBar"></userrightbar>
  </div>

</div>


<!-- /#page-wrapper -->
<!--footer start-->

<!--footer end-->
