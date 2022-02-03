<div class="w-1/5 bgColor-sec h-24 shadow rounded color-3-700 py-4 px-4 mr-5 pointer ">
 <div class="fx fx-j-c ls-wider">
   <i class="fa fa-badge-check fas-xl mr-1 color-pri"></i>
   <div class="fs-xl color-3-700">

     <?=$booking->confirmed();?>

    <h6 class="color-3-600 fs-xs py-3">Confirmed bookings</h6>
   </div>
 </div>
</div>

<div class="w-1/5 bgColor-sec h-24 shadow rounded color-3-700 py-4 px-4 mr-5 pointer">
 <div class="fx fx-j-c ls-wider">
   <i class="fa fa-exclamation-circle fas-xl mr-1 color-1"></i>
   <div class="fs-xl color-3-700">

     <?=$booking->pending();?>

    <h6 class="color-3-600 fs-xs py-3">Pending bookings</h6>
   </div>
 </div>
</div>

<div class="w-1/5 bgColor-sec h-24 shadow rounded color-3-700 py-4 px-4 mr-5 pointer">
<div class="fx fx-j-c ls-wider">

  <i class="fas fa-ban  fas-xl mr-1 color-danger"></i>
  <div class="fs-xl color-3-700">

    <?=$booking->rejected();?>

   <h6 class="color-3-600 fs-xs py-3">Rejected bookings</h6>
  </div>

</div>
</div>
