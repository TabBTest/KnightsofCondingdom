<div class='row'>
    
    <form id='overrides-user-search-form'>    
        <div class='col-xs-12 form-group'>
            <label>Business Name:&nbsp;&nbsp; <input type='text' name='filter[businessName]' class='form-control' /> </label>
            <label>First Name:&nbsp;&nbsp; <input type='text' name='filter[firstName]' class='form-control' /> </label>
            <label>Last Name:&nbsp;&nbsp; <input type='text' name='filter[lastName]' class='form-control' /> </label>
            <label>Email:&nbsp;&nbsp; <input type='text' name='filter[email]' class='form-control' /> </label>
            <label style='vertical-align: bottom'>&nbsp;<button type='button' class='btn btn-info' onclick='Vendors.search();'>Search</button></label>
        </div>
    </form>
    
    <div class="col-xs-12 overrides-user-body">
        <?php echo $this->render('_user_list', ['vendors' => $vendors]);?>
    </div>
</div>