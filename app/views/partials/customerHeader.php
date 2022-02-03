 <a class="no-line" href="#templates/customer/booking/account">
      <div class="fx fx-i-c">
        <div class="rounded-full bgColor-pri h-10 w-10">
           <img class="w-full h-full rounded-full cover" src="<?=url($user->photo, 'images.user');?> " alt="user pic">
        </div>
       <h5 class="ml-3 color-2-800"> <?=$user->fullName;?> </h5>
      </div>
    </a>

    <button class="bgColor-pri color-sec fs-md py-2 px-3 border-0 rounded
    fw-bold ff-pri pointer outline-0"
    type="button" name="button"
    onclick="route.set(`auth\\LogOutController/logOut/check = 1/check`)">
    <i class="fa fa-sign-out"></i> Logout
    </button>
