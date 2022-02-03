<div class="w-2/5 h-auto bgColor-3-100 ">

<div class="fixed w-2/5 h-screen bgColor-3-100 ">
  <form id="updateAccountForm" class="w-10/12 py-5 m-0-auto fx fx-c">
  <div id="UpdateAccount">
    <h4 class="color-2-800 mb-5 txt-h-c">Update Account</h4>
    <div id="user_photo" class="rounded-full bgColor-pri h-20 w-20 m-0-auto">
      <label for="userPhoto-upload">
        <img class="w-full h-full rounded-full cover" src="<?=url($user->photo, 'images.user')?>" alt="user pic">
      </label>
      <input type="file" id="userPhoto-upload" name="photo" multiple style="display:none;"
      onchange="$('#user_photo img').attr('src', window.URL.createObjectURL(this.files[0]))"/>
    </div>

  <div class="fx fx-j-btw">
   <label class="pt-5 fw-bold color-2-700" for="fullName">Name:</label>
    <input class="w-10/12 mt-4 py-2 px-3 color-2-800 holder:color-3-600 fw-bold
     b-solid border-2 b-color-pri-100 rounded outline-0 focus:b-color-pri"
    type="text" name="fullName" value=" <?=$user->fullName?> ">
  </div>

  <div class="fx fx-j-btw">
   <label class="pt-5 fw-bold color-2-700" for="fullName">Email:</label>
    <input class="w-10/12 mt-4 py-2 px-3 color-2-800 holder:color-3-600 fw-bold
     b-solid border-2 b-color-pri-100 rounded outline-0 focus:b-color-pri"
    type="text" name="email" value=" <?=$user->email?> ">
  </div>

  <div class="fx fx-j-btw">
   <label class="pt-5 fw-bold color-2-700" for="fullName">Phone:</label>
    <input class="w-10/12 mt-4 py-2 px-3 color-2-800 holder:color-3-600 fw-bold
     b-solid border-2 b-color-pri-100 rounded outline-0 focus:b-color-pri"
    type="text" name="phone" value=" <?=$user->phone;?> ">
  </div>

  <div class="fx fx-j-btw">
   <label for="Password"></label>
    <input id="getChangePassword" class="w-10/12 mt-4 bgColor-sec py-2 px-3
      color-pri fw-bold border-1 b-solid b-color-2-100 rounded outline-0  pointer"
    type="button"  value="Change Password">
  </div>

  <input type="hidden" name="id" value="<?=$user->id;?>">

  <input type="hidden" name="for" value="customer">


  <span id="response_box"></span>

  <div class="fx fx-j-btw">
   <label class="pt-5 fw-bold color-2-700" for=""></label>
   <button id="status_btn" class="w-10/12 mt-4 bgColor-sec py-3 px-3  color-pri fw-black
    fs-md shadow b-solid border-2 b-color-pri-100 pointer rounded outline-0"
    type="button"  onclick="route.set('updateAccount')">Save Changes</button>
  </div>

</div>

<div id="ChangePasword" class="hide">
      <h4 class="color-2-800 txt-h-c">Change Password</h4>
  <div class="fx fx-c">
    <input class="w-11/12 mt-4 py-3 px-3 color-2-800 holder:color-3-600 fw-bold
     b-solid border-2 b-color-pri-100 rounded outline-0 focus:b-color-pri"
    type="password" name="password" placeholder="Enter Current password">
  </div>

  <div class="fx fx-c">
    <input class="w-11/12 mt-4 py-3 px-3 color-2-800 holder:color-3-600 fw-bold
     b-solid border-2 b-color-pri-100 rounded outline-0 focus:b-color-pri"
    type="password" name="newPassword"  placeholder="Enter new password">
  </div>

   <span id="pass_response_box"></span>

  <button id="pass_status_btn" class="w-11/12 mt-4 bgColor-sec py-3 px-3  color-pri fw-bold
   fs-md shadow b-solid border-2 b-color-pri-100 pointer rounded outline-0"
   type="button" onclick="route.set('changePassword')">Save Changes</button>

   <button id="getUpdateAccount" class="w-11/12 mt-10 bgColor-sec py-3 px-3
   color-pri fs-md shadow border-0 pointer rounded outline-0"
   type="button">Go back</button>
</div>
  </form>
</div>

</div>

<div class="w-3/5 h-64 bgColor-3-100 ">

  <?php if ($user->bookings[0]->status): ?>

  <table class="table-auto border-collapse bgColor-sec">
      <caption class="fw-black color-2-800 py-5">Booking History</caption>
    <thead>
      <tr class="">
        <th class="px-4 py-2 color-2-700"></th>
        <th class="px-4 py-2 color-2-700">Artist</th>
        <th class="px-4 py-2 color-2-700">Date</th>
        <th class="px-4 py-2 color-2-700">From</th>
        <th class="px-4 py-2 color-2-700">To</th>
        <th class="px-4 py-2 color-2-700">Cost</th>
        <th class="px-4 py-2 color-2-700">Status</
      </tr>
    </thead>

    <tbody>

         <?php foreach ($user->bookings as $booking): ?>

           <tr class="pointer bgColor-sec border-8 b-solid b-color-3-100">
             <td class="px-4 py-2 color-3-600 border-0  ">
               <div class="rounded-full bgColor-pri h-8 w-8">
                 <img class="w-full h-full rounded-full cover" src="<?=url($booking->artist->photo, 'images.artist');?>" alt="">
               </div>
             </td>
             <td class="px-4 py-2 color-3-600 border-0"> <?=$booking->artist->name;?> </td>
             <td class="px-4 py-2 color-3-600 border-0"> <?=$booking->book_date;?> </td>
             <td class="px-4 py-2 color-3-600 border-0"> <?=date('h:i A', strtotime($booking->from_time));?> </td>
            <td class="px-4 py-2 color-3-600 border-0"> <?=date('h:i A', strtotime($booking->to_time));?> </td>
            <td class="px-4 py-2 color-3-600 border-0"> Ksh <?=$booking->cost;?> </td>

            <?php if ($booking->status === 'confirmed') : ?>

              <td class="px-4 py-2  color-pri"> <?=$booking->status;?> </td>

            <?php elseif ($booking->status === 'pending') : ?>

              <td class="px-4 py-2  color-3-600"> <?=$booking->status;?> </td>

            <?php else :?>

              <td class="px-4 py-2  color-danger"> <?=$booking->status;?> </td>

            <?php endif; ?>
           </tr>

         <?php endforeach; ?>

    </tbody>

  </table>

    <?php else: ?>

      <div class="w-full txt-h-c">

      <img class="w-4/12 mt-20 mr-10" src='<?=url('empty.svg', 'images.svg')?>' alt="empty">

       <p class="color-pri mt-5 fs-pri">No booking history found</p>

      </div>

    <?php endif; ?>

</div>
