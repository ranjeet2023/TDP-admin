<!--begin::Aside-->
<style>

.aside-dark .menu .menu-item .menu-link .menu-title {
    color: #fff !important;
}
.aside-dark .menu .menu-item .menu-link .menu-icon .svg-icon svg [fill]:not(.permanent):not(g) {
    transition: fill .3s ease;
    fill: #fff;
}

.aside-dark .menu .menu-item .menu-link:hover:not(.disabled):not(.active) .menu-icon .svg-icon svg [fill]:not(.permanent):not(g), .aside-dark .menu .menu-item.hover > .menu-link:not(.disabled):not(.active) .menu-icon .svg-icon svg [fill]:not(.permanent):not(g) {
    transition: fill .3s ease;
    fill: gray;
}

.container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 100%;
}
.aside.aside-dark .aside-toggle svg [fill]:not(.permanent):not(g) {
    transition: fill .3s ease;
    fill: #fff;
}

[data-kt-aside-minimize="on"] .aside:not(.aside-hoverable) .aside-logo .logo, [data-kt-aside-minimize="on"] .aside:not(:hover) .aside-logo .logo {
    display: block;
    padding-left: 160px;
}

.aside-dark .menu .menu-item .menu-link.active {
    transition: color .2s ease,background-color .2s ease;
    background-color: #0a0a6e;
    color: #fff;
}
.error {color: #f1416c;}
</style>

<div id="kt_aside" class="aside aside-dark" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ url('admin') }}">
            <img alt="Logo" src="{{asset('assets/images/logo-white.png')}}" class="h-40px logo" />
        </a>
        <!--end::Logo-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->

            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                <div class="menu-item menu-item-active" aria-haspopup="true">
                    <a href="{{ url('admin') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11 2.375L2 9.575V20.575C2 21.175 2.4 21.575 3 21.575H9C9.6 21.575 10 21.175 10 20.575V14.575C10 13.975 10.4 13.575 11 13.575H13C13.6 13.575 14 13.975 14 14.575V20.575C14 21.175 14.4 21.575 15 21.575H21C21.6 21.575 22 21.175 22 20.575V9.575L13 2.375C12.4 1.875 11.6 1.875 11 2.375Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                @php
                \DB::enableQueryLog();
                    $permission = DB::table('user_has_permission')->join('permission', 'permission.permission_id', '=', 'user_has_permission.permission_id')->where('user_id', Auth::user()->id)
                    ->where(function($query){
                        $query->orWhere('full','=','1')
                        ->orWhere('menu','=','1')
                        ->orWhere('edit','=','1')
                        ->orWhere('delete','=','1');
                    })->get()->toArray();

                    //{{dd($permission);}}
                    // dd(\DB::getQueryLog());
                    // select * from `user_has_permission` inner join `permission` on `permission`.`permission_id` = `user_has_permission`.`permission_id` where `user_id` = 493 and (`full` = 1 or `menu` = 1 or `edit` = 1 or `delete` = 1)
                    // dd($permission);
                    //var_dump(array_search($request->segment(1), array_column($permission, 'url')));
                    // // die;
                    // if (array_search($request->segment(1), array_column($permission, 'url')) === FALSE) {
                    //     echo "redirect";
                    //     // return Redirect::to("/");
                    // }
                    // echo "<pre>";
                    // print_r($permission);
                    // die;
                    $customer = array('add-customer','customer-list','gcustomer','scustomer','pcustomer','deletedcustomer','place-order','leads-list','create-email-template');
                    $customer_result = array_intersect($customer,array_column($permission, 'url'));

                    $supplier = array('add-suppliers','suppliers-list','pending-suppliers-list','suppliers-edit','deleted-supplier-list','expired-report','last-deleted-stones');
                    $supplier_result = array_intersect($supplier,array_column($permission, 'url'));

                    $staff = array('add-staff','manage-staff');
                    $staff_result = array_intersect($staff,array_column($permission, 'url'));

                    $associate =array('add-associate','manage-associate');
                    $associate_result = array_intersect($associate,array_column($permission, 'url'));

                    $unloaded = array('add-diamond','unloaded-natural-diamond','unloaded-lab-diamond','admin-upload-diamond','replacement-diamond');
                    $unloaded_result = array_intersect($unloaded,array_column($permission, 'url'));

                    $order = array('order-list','order-list-new','order-list-sales','cart-list','enquiry-list','enquiry-list-detail','hold-diamond-list','invoice-list','admin-perfoma-invoice-list','image-video-request');
                    $order_result = array_intersect($order,array_column($permission, 'url'));

                    $return = array('return-diamond-list');
                    $return_result = array_intersect($return,array_column($permission, 'url'));

                    $account = array('purchase-list','purchase-bill','purchase-bill-form');
                    $account_result = array_intersect($account,array_column($permission, 'url'));

                    $logistic = array('pickup-list','pickup-done-list','return-list','export-list','inout-list');
                    $logistic_result = array_intersect($logistic,array_column($permission, 'url'));

                    $price = array('pricesetting','shippingpricesetting');
                    $price_result = array_intersect($price,array_column($permission, 'url'));

                    $log = array('login-history-customer','login-history-supplier','login-history-staff','api-history','page-visits', 'api-history-detail');
                    $log_result = array_intersect($log,array_column($permission, 'url'));

                    $extra = array('red-alert','permission','add-job','manage-job','currency-exchange','daily-reporting','daily-check-list');
                    $extra_result = array_intersect($extra,array_column($permission, 'url'));

                @endphp

                @if($customer_result != FALSE)
                @if (in_array(Auth::user()->role_id, [3]))
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['customer-list','pcustomer','scustomer','gcustomer','customer-edit','deletedcustomer','place-order','leads-list','leads-report-user-detail','leads-report-detail','add-new-leads','leads-edit','leads-report','create-email-template'])) ? 'show' : ''; }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor"/>
                                    <path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Customers</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('add-customer', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link" href="{{ url('add-customer') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Add Customer</span>
							</a>
						</div>
                        @endif

                        @if (array_search('customer-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['customer-list'])) ? 'active' : ''; }}" href="{{ route('customer') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Customer</span>
							</a>
						</div>
                        @endif

                        @if (array_search('gcustomer', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['gcustomer'])) ? 'active' : ''; }}" href="{{ url('gcustomer') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Gold Customer</span>
							</a>
						</div>
                        @endif

                        @if (array_search('scustomer', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['scustomer'])) ? 'active' : ''; }}" href="{{ url('scustomer') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Silver Customer</span>
							</a>
						</div>
                        @endif
                        @if (array_search('pcustomer', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['pcustomer'])) ? 'active' : ''; }}" href="{{ route('customer.pending') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Pending Customer</span>
							</a>
						</div>
                        @endif
                        @if (array_search('deletedcustomer', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['deletedcustomer'])) ? 'active' : ''; }}"" href="{{ url('deletedcustomer') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Deleted Customer</span>
							</a>
						</div>
                        @endif
                        @if (array_search('place-order', array_column($permission, 'url')) !== FALSE)
                            <div class="menu-item" aria-haspopup="true">
                                <a class="menu-link {{ (in_array(Request::segment(1), ['place-order'])) ? 'active' : ''; }}" href="{{ url('place-order') }}">
                                    <i class="menu-bullet menu-bullet-dot">
                                    <span></span></i>
                                    <span class="menu-title">Place Order</span>
                                </a>
                            </div>
                        @endif
                        @if(array_search('leads-list', array_column($permission, 'url')) !== FALSE)
                            <div class="menu-item" aria-haspopup="true">
                                <a class="menu-link {{ (in_array(Request::segment(1), ['leads-list','leads-report','leads-report-detail','leads-report-user-detail','add-new-leads','leads-edit','create-email-template'])) ? 'active' : ''; }}" href="{{ url('leads-list') }}">
                                    <i class="menu-bullet menu-bullet-dot">
                                    <span></span></i>
                                    <span class="menu-title">Leads List</span>
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
                @endif
                @endif

                @if($supplier_result != FALSE)
				@if (in_array(Auth::user()->role_id, [3,4]))
				<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['add-suppliers','suppliers-list','pending-suppliers-list','suppliers-edit','deleted-supplier-list','expired-report','suppliers-all-diamond','last-deleted-stones'])) ? 'show' : ''; }}" aria-haspopup="true" data-menu-toggle="hover">
                    <span class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" width="24px" height="24px">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <path d="M436.1,223.9c0-112.8,91.4-204.2,204.1-204.2c112.7,0,204.2,91.4,204.2,204.2c0,112.7-91.4,204.1-204.2,204.1C527.5,428,436.1,336.6,436.1,223.9L436.1,223.9L436.1,223.9z M726.8,448.9H553.6c-71,0-135.4,15.5-182.6,62.1h277.2v419.5h-135c33.9,16.7,33.9,18.9,33.9,28.8c0,3.7,0,9-3.2,13.2c42.5,4.7,82.1,7.3,118.8,7.3c192.1,0,303.5-54,310.3-57.5l13.7-6.1h3.3V703.3c0-144.1-119-254.3-263.1-254.3H726.8L726.8,448.9z M515.9,961.5c0,10.4-84.2,18.8-189.8,18.8c-105.5,0-191.1-8.4-191.1-18.8c0-7.4,45.9-13.8,108-16.8v-28.4H10V527.8h608.3v388.4H382.9v27.5C461.8,946.2,515.9,953.2,515.9,961.5L515.9,961.5L515.9,961.5z M571.7,884V557.7H56.6V884H571.7L571.7,884z M148.6,854.1h43V667.6h-43V854.1L148.6,854.1z M213.2,854.1H243V714.2h-29.9V854.1L213.2,854.1z M258.6,854.1h43V621h-43V854.1L258.6,854.1z M421.1,854.1h43V667.6h-43V854.1L421.1,854.1z M368.5,854.1h31.1V714.2h-31.1V854.1L368.5,854.1z M323.1,854.1H353V650.9h-29.9V854.1L323.1,854.1z" fill="#000000"/>
                                </g>
                            </svg>
                        </span>
                        <span class="menu-title">Suppliers</span>
                        <i class="menu-arrow"></i>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <i class="menu-arrow"></i>
                        <div class="menu-subnav">
                            @if (array_search('add-suppliers', array_column($permission, 'url')) !== FALSE)
                            <div class="menu-item" aria-haspopup="true">
								<a class="menu-link {{ (Request::segment(1) == 'add-suppliers') ? 'active' : ''; }}" href="{{ url('add-suppliers') }}">
									<i class="menu-bullet menu-bullet-dot">
									<span></span></i>
									<span class="menu-title">Add Suppliers</span>
								</a>
							</div>
                            @endif
                            @if (array_search('pending-suppliers-list', array_column($permission, 'url')) !== FALSE)
							<div class="menu-item" aria-haspopup="true">
								<a class="menu-link {{ (Request::segment(1) == 'pending-suppliers-list') ? 'active' : ''; }}" href="{{ url('pending-suppliers-list') }}">
									<i class="menu-bullet menu-bullet-dot">
									<span></span></i>
									<span class="menu-title">Pending Suppliers</span>
								</a>
							</div>
                            @endif
                            @if (array_search('suppliers-list', array_column($permission, 'url')) !== FALSE)
							<div class="menu-item" aria-haspopup="true">
								<a class="menu-link {{ (Request::segment(1) == 'suppliers-list' || Request::segment(1) == 'suppliers-all-diamond') ? 'active' : ''; }}" href="{{ url('suppliers-list') }}">
									<i class="menu-bullet menu-bullet-dot">
									<span></span></i>
									<span class="menu-title">Suppliers</span>
								</a>
							</div>
                            @endif
                            @if (array_search('deleted-supplier-list', array_column($permission, 'url')) !== FALSE)
							<div class="menu-item " aria-haspopup="true">
								<a class="menu-link {{ (Request::segment(1) == 'deleted-supplier-list') ? 'active' : ''; }}" href="{{ url('deleted-supplier-list') }}">
									<i class="menu-bullet menu-bullet-dot">
									<span></span></i>
									<span class="menu-title">Deleted Suppliers</span>
								</a>
							</div>
                            @endif
                            @if (array_search('expired-report', array_column($permission, 'url')) !== FALSE)
							<div class="menu-item" aria-haspopup="true">
								<a class="menu-link {{ (Request::segment(1) == 'expired-report') ? 'active' : ''; }}" href="{{ url('expired-report') }}">
									<i class="menu-bullet menu-bullet-dot">
									<span></span></i>
									<span class="menu-title">Expired Reports</span>
								</a>
							</div>
                            @endif
                            @if (array_search('last-deleted-stones', array_column($permission, 'url')) !== FALSE)
							<div class="menu-item" aria-haspopup="true">
								<a class="menu-link {{ (Request::segment(1) == 'last-deleted-stones') ? 'active' : ''; }}" href="{{ url('last-deleted-stones') }}">
									<i class="menu-bullet menu-bullet-dot">
									<span></span></i>
									<span class="menu-title">Last Deleted Stones</span>
								</a>
							</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endif

                @if($staff_result != FALSE)
                @if (in_array(Auth::user()->role_id, [3]))
				<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['add-staff','manage-staff'])) ? 'show' : ''; }}" aria-haspopup="true" data-menu-toggle="hover">
					<a href="javascript:;" class="menu-link menu-toggle">
						<span class="svg-icon menu-icon">
							<svg width="24px" height="24px" viewBox="-42 0 512 512.002" xmlns="http://www.w3.org/2000/svg">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<path d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0" fill="#000000" fill-rule="nonzero"/>
									<path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0" fill="#000000"/>
								</g>
							</svg>
						</span>
						<span class="menu-title">Staff</span>
						<i class="menu-arrow"></i>
					</a>
					<div class="menu-sub menu-sub-accordion menu-active-bg">
						<i class="menu-arrow"></i>
						<ul class="menu-subnav">
							@if (Auth::user()->user_type == 1)
                            @if (array_search('add-staff', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link" href="{{ url('add-staff') }}">
										<i class="menu-bullet menu-bullet-dot">
										<span></span></i>
										<span class="menu-title">Add Staff</span>
									</a>
								</li>
                            @endif
							@endif
							@if (Auth::user()->user_type == 1)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link" href="{{ url('manage-staff') }}">
										<i class="menu-bullet menu-bullet-dot">
											<span></span>
										</i>
										<span class="menu-title">Manage Staff</span>
									</a>
								</li>
							@endif
						</ul>
					</div>
				</div>
                @endif
                @endif

                @if($associate_result != FALSE)
                @if (in_array(Auth::user()->role_id, [3]))
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['add-associate','manage-associate'])) ? 'show' : ''; }}" aria-haspopup="true" data-menu-toggle="hover">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M18.0624 15.3454L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3454C4.56242 13.6454 3.76242 11.4452 4.06242 8.94525C4.56242 5.34525 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24525 19.9624 9.94525C20.0624 12.0452 19.2624 13.9454 18.0624 15.3454ZM13.0624 10.0453C13.0624 9.44534 12.6624 9.04534 12.0624 9.04534C11.4624 9.04534 11.0624 9.44534 11.0624 10.0453V13.0453H13.0624V10.0453Z" fill="currentColor"/>
                                    <path d="M12.6624 5.54531C12.2624 5.24531 11.7624 5.24531 11.4624 5.54531L8.06241 8.04531V12.0453C8.06241 12.6453 8.46241 13.0453 9.06241 13.0453H11.0624V10.0453C11.0624 9.44531 11.4624 9.04531 12.0624 9.04531C12.6624 9.04531 13.0624 9.44531 13.0624 10.0453V13.0453H15.0624C15.6624 13.0453 16.0624 12.6453 16.0624 12.0453V8.04531L12.6624 5.54531Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Associates</span>
                        <span class="menu-arrow"></span>
                    </span>
					<div class="menu-sub menu-sub-accordion menu-active-bg">
						<i class="menu-arrow"></i>
						<ul class="menu-subnav">
							@if (Auth::user()->user_type == 1)
                            @if (array_search('add-associate', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link" href="{{ url('add-associate') }}">
										<i class="menu-bullet menu-bullet-dot">
										<span></span></i>
										<span class="menu-title">Add Associate</span>
									</a>
								</li>
                            @endif
							@endif
							@if (Auth::user()->user_type == 1)
                            @if (array_search('manage-associate', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link" href="{{ url('manage-associate') }}">
										<i class="menu-bullet menu-bullet-dot">
											<span></span>
										</i>
										<span class="menu-title">Manage Associate</span>
									</a>
								</li>
                            @endif
							@endif
						</ul>
					</div>
				</div>
                @endif
                @endif

                @if($unloaded_result != FALSE)
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['add-diamond','unloaded-natural-diamond','unloaded-lab-diamond','admin-upload-diamond','replacement-diamond'])) ? 'show' : ''; }}" aria-haspopup="true" data-menu-toggle="hover">
					<a href="javascript:;" class="menu-link menu-toggle">
						<span class="svg-icon menu-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M21.7 11.3L12.7 2.3C12.5 2.1 12.2 2 12 2C11.7 2 11.5 2.1 11.3 2.3L2.3 11.3C1.9 11.7 1.9 12.3 2.3 12.7L11.3 21.7C11.5 21.9 11.8 22 12 22C12.3 22 12.5 21.9 12.7 21.7L21.7 12.7C22.1 12.3 22.1 11.7 21.7 11.3ZM12 7.2L14.8 10H9.3L12 7.2ZM12 16.8L9.19999 14H14.7L12 16.8Z" fill="currentColor"/>
                            </svg>
						</span>
						<span class="menu-title">Diamond Adding</span>
						<i class="menu-arrow"></i>
					</a>
					<div class="menu-sub menu-sub-accordion menu-active-bg">
						<i class="menu-arrow"></i>
						<ul class="menu-subnav">
                            @if (array_search('replacement-diamond', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link {{ (Request::segment(1) == 'replacement-diamond') ? 'active' : ''; }}" href="{{ url('replacement-diamond') }}">
										<i class="menu-bullet menu-bullet-dot">
										<span></span></i>
										<span class="menu-title">Replacement Diamond</span>
									</a>
								</li>
                            @endif

                            @if (array_search('unloaded-natural-diamond', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link {{ (Request::segment(1) == 'unloaded-natural-diamond') ? 'active' : ''; }}" href="{{ url('unloaded-natural-diamond') }}">
										<i class="menu-bullet menu-bullet-dot">
										<span></span></i>
										<span class="menu-title">Unloaded Natural</span>
									</a>
								</li>
                            @endif
                            @if (array_search('unloaded-lab-diamond', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link {{ (Request::segment(1) == 'unloaded-lab-diamond') ? 'active' : ''; }}" href="{{ url('unloaded-lab-diamond') }}">
										<i class="menu-bullet menu-bullet-dot">
											<span></span>
										</i>
										<span class="menu-title">Unloaded Lab Grown</span>
									</a>
								</li>
                            @endif
                            @if (array_search('add-diamond', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link {{ (Request::segment(1) == 'add-diamond') ? 'active' : ''; }}" href="{{ url('add-diamond') }}">
										<i class="menu-bullet menu-bullet-dot">
											<span></span>
										</i>
										<span class="menu-title">Add Diamond</span>
									</a>
								</li>
                            @endif
                            @if (array_search('admin-upload-diamond', array_column($permission, 'url')) !== FALSE)
								<li class="menu-item" aria-haspopup="true">
									<a class="menu-link {{ (Request::segment(1) == 'admin-upload-diamond') ? 'active' : ''; }}" href="{{ url('admin-upload-diamond') }}">
										<i class="menu-bullet menu-bullet-dot">
											<span></span>
										</i>
										<span class="menu-title">Upload Diamond</span>
									</a>
								</li>
                            @endif
						</ul>
					</div>
				</div>
                @endif

                @if (in_array(Auth::user()->role_id, [3,4]))
                @if (array_search('diamond_natural', array_column($permission, 'url')) !== FALSE)
                <div class="menu-item menu-item-active" aria-haspopup="true">
                    <a href="{{ url('diamond_natural') }}" class="menu-link {{ (in_array(Request::segment(1), ['diamond_natural', 'diamond_natural_list'])) ? 'active' : ''; }}">
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.8 6.4L16.7 9.2V14.8L11.8 17.6L6.89999 14.8V9.2L11.8 6.4ZM11.8 2C11.5 2 11.2 2.1 11 2.2L3.79999 6.4C3.29999 6.7 3 7.3 3 7.9V16.2C3 16.8 3.29999 17.4 3.79999 17.7L11 21.9C11.3 22.1 11.5 22.1 11.8 22.1C12.1 22.1 12.4 22 12.6 21.9L19.8 17.7C20.3 17.4 20.6 16.8 20.6 16.2V7.9C20.6 7.3 20.3 6.7 19.8 6.4L12.6 2.2C12.4 2.1 12.1 2 11.8 2Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="menu-title">Natural Diamond</span>
                    </a>
                </div>
                @endif
                @endif
                @if (in_array(Auth::user()->role_id, [3,4]))
                @if (array_search('diamond_labgrown', array_column($permission, 'url')) !== FALSE)
                <div class="menu-item menu-item-active" aria-haspopup="true">
                    <a href="{{ url('diamond_labgrown') }}" class="menu-link {{ (in_array(Request::segment(1), ['diamond_labgrown', 'diamond_labgrown_list'])) ? 'active' : ''; }}">
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M11.8 5.2L17.7 8.6V15.4L11.8 18.8L5.90001 15.4V8.6L11.8 5.2ZM11.8 2C11.5 2 11.2 2.1 11 2.2L3.8 6.4C3.3 6.7 3 7.3 3 7.9V16.2C3 16.8 3.3 17.4 3.8 17.7L11 21.9C11.3 22 11.5 22.1 11.8 22.1C12.1 22.1 12.4 22 12.6 21.9L19.8 17.7C20.3 17.4 20.6 16.8 20.6 16.2V7.9C20.6 7.3 20.3 6.7 19.8 6.4L12.6 2.2C12.4 2.1 12.1 2 11.8 2Z" fill="currentColor"/>
                                <path d="M11.8 8.69995L8.90001 10.3V13.7L11.8 15.3L14.7 13.7V10.3L11.8 8.69995Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="menu-title">Lab Grown Diamond</span>
                    </a>
                </div>
                @endif
                @endif

                @if (in_array(Auth::user()->role_id, [3,4]))
                    @if (array_search('diamond-status', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-item menu-item-active" aria-haspopup="true">
                        <a href="{{ url('diamond-status') }}" class="menu-link {{ (in_array(Request::segment(1), ['diamond-status'])) ? 'active' : ''; }}">
                            <span class="svg-icon menu-icon">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M7.16973 20.95C6.26973 21.55 5.16972 20.75 5.46972 19.75L7.36973 14.05L2.46972 10.55C1.56972 9.95005 2.06973 8.55005 3.06973 8.55005H20.8697C21.9697 8.55005 22.3697 9.95005 21.4697 10.55L7.16973 20.95Z" fill="currentColor"/>
                                    <path d="M11.0697 2.75L7.46973 13.95L16.9697 20.85C17.8697 21.45 18.9697 20.65 18.6697 19.65L13.1697 2.75C12.7697 1.75 11.3697 1.75 11.0697 2.75Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <span class="menu-title">Diamond Status</span>
                        </a>
                    </div>
                    @endif
                @endif

                @if (in_array(Auth::user()->role_id, [3,4]))
                    @if (array_search('parcel-goods-list', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-item menu-item-active" aria-haspopup="true">
                        <a href="{{ url('parcel-goods-list') }}" class="menu-link {{ (in_array(Request::segment(1), ['parcel-goods-list'])) ? 'active' : ''; }}">
                            <span class="svg-icon menu-icon">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 9V11C21 11.6 20.6 12 20 12H14V8H20C20.6 8 21 8.4 21 9ZM10 8H4C3.4 8 3 8.4 3 9V11C3 11.6 3.4 12 4 12H10V8Z" fill="currentColor"/>
                                    <path d="M15 2C13.3 2 12 3.3 12 5V8H15C16.7 8 18 6.7 18 5C18 3.3 16.7 2 15 2Z" fill="currentColor"/>
                                    <path opacity="0.3" d="M9 2C10.7 2 12 3.3 12 5V8H9C7.3 8 6 6.7 6 5C6 3.3 7.3 2 9 2ZM4 12V21C4 21.6 4.4 22 5 22H10V12H4ZM20 12V21C20 21.6 19.6 22 19 22H14V12H20Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <span class="menu-title">Parcel Goods</span>
                        </a>
                    </div>
                    @endif
                @endif


                @if (array_search('match-pair', array_column($permission, 'url')) !== FALSE)
                <div class="menu-item menu-item-active" aria-haspopup="true">
                    <a href="{{ url('match-pair') }}" class="menu-link {{ (in_array(Request::segment(1), ['match-pair'])) ? 'active' : ''; }}">
                        <span class="svg-icon menu-icon">
                            <svg fill="#FFF" version="1.1" id=" " xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 46.758 46.758" xml:space="preserve">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier"> <g> <g> <g>
                                    <path d="M11.138,46.741c-1.023,0-1.987-0.398-2.712-1.123l-7.305-7.306c-1.495-1.495-1.494-3.928,0-5.423l16.614-16.615 c1.496-1.496,3.931-1.494,5.424,0l5.126,5.126c0.391,0.391,0.391,1.023,0,1.414c-0.392,0.391-1.023,0.391-1.414,0l-5.126-5.126 c-0.716-0.715-1.879-0.715-2.596,0L2.536,34.305c-0.716,0.716-0.716,1.88,0,2.595l7.305,7.306c0.693,0.692,1.902,0.692,2.596,0 l11.431-11.431c0.391-0.391,1.022-0.391,1.414,0c0.391,0.392,0.391,1.023,0,1.414L13.851,45.619 C13.125,46.343,12.163,46.741,11.138,46.741z"></path> </g> <g> <path d="M26.311,31.566c-0.981,0-1.965-0.374-2.712-1.121l-5.126-5.126c-0.391-0.391-0.391-1.022,0-1.414 c0.392-0.391,1.023-0.391,1.414,0l5.126,5.126c0.715,0.716,1.879,0.716,2.595,0l16.615-16.615c0.715-0.715,0.715-1.88,0-2.595 l-7.306-7.305c-0.693-0.693-1.901-0.693-2.596,0l-11.43,11.43c-0.391,0.391-1.022,0.391-1.414,0 c-0.391-0.391-0.391-1.023,0-1.414l11.431-11.43c1.448-1.449,3.975-1.449,5.423,0l7.306,7.305c1.494,1.496,1.494,3.929,0,5.423 L29.021,30.445C28.273,31.192,27.292,31.566,26.311,31.566z"></path>
                                </g> </g> </g> </g>
                            </svg>
                        </span>
                        <span class="menu-title">Match Pair</span>
                    </a>
                </div>
                @endif

                @if($order_result != FALSE)
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['order-list','order-list-new','order-list-sales','cart-list','enquiry-list','enquiry-list-detail','hold-diamond-list','admin-release-list','invoice-list','admin-perfoma-invoice-list','image-video-request'])) ? 'show' : ''; }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M13.0079 2.6L15.7079 7.2L21.0079 8.4C21.9079 8.6 22.3079 9.7 21.7079 10.4L18.1079 14.4L18.6079 19.8C18.7079 20.7 17.7079 21.4 16.9079 21L12.0079 18.8L7.10785 21C6.20785 21.4 5.30786 20.7 5.40786 19.8L5.90786 14.4L2.30785 10.4C1.70785 9.7 2.00786 8.6 3.00786 8.4L8.30785 7.2L11.0079 2.6C11.3079 1.8 12.5079 1.8 13.0079 2.6Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Orders</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('order-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['order-list'])) ? 'active' : ''; }}" href="{{ url('order-list') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Order List</span>
							</a>
						</div>
                        @endif
                        @if (array_search('order-list-new', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['order-list-new'])) ? 'active' : ''; }}" href="{{ url('order-list-new') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Order List New</span>
							</a>
						</div>
                        @endif
                        @if (array_search('order-list-sales', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['order-list-sales'])) ? 'active' : ''; }}" href="{{ url('order-list-sales') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Sale List</span>
							</a>
						</div>
                        @endif
                        @if (array_search('cart-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['cart-list'])) ? 'active' : ''; }}" href="{{ url('cart-list') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Cart List</span>
							</a>
						</div>
                        @endif
                        @if (array_search('enquiry-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['enquiry-list','enquiry-list-detail','hold-diamond-list','admin-release-list'])) ? 'active' : ''; }}" href="{{ url('enquiry-list') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Enquiry List</span>
							</a>
						</div>
                        @endif
                        @if (array_search('invoice-list', array_column($permission, 'url')) !== FALSE)
                        <div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['invoice-list'])) ? 'active' : ''; }}" href="{{ url('invoice-list') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Invoice List</span>
							</a>
						</div>
                        @endif
                        @if (array_search('admin-perfoma-invoice-list', array_column($permission, 'url')) !== FALSE)
                        <div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['admin-perfoma-invoice-list'])) ? 'active' : ''; }}" href="{{ url('admin-perfoma-invoice-list') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Perfoma List</span>
							</a>
						</div>
                        @endif
                        @if (array_search('image-video-request', array_column($permission, 'url')) !== FALSE)
                        <div class="menu-item" aria-haspopup="true">
                            <a class="menu-link {{ (in_array(Request::segment(1), ['image-video-request'])) ? 'active' : ''; }}" href="{{ url('image-video-request') }}">
                                <i class="menu-bullet menu-bullet-dot">
                                <span></span></i>
                                <span class="menu-title">Image Video Request</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($logistic_result != FALSE)
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['pickup-list','pickup-done-list','return-list','export-list','inout-list'])) ? 'show' : ''; }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z" fill="currentColor"/>
                                    <path opacity="0.3" d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Logistics</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('pickup-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['pickup-list'])) ? 'active' : ''; }}" href="{{ url('pickup-list') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">QC List</span>
							</a>
						</div>
                        @endif
                        <!-- @if (array_search('pickup-done-list', array_column($permission, 'url')) !== FALSE) -->
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['return-list'])) ? 'active' : ''; }}" href="{{ url('return-list') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Return List</span>
							</a>
						</div>
                        <!-- @endif -->
                        @if (array_search('export-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['export-list'])) ? 'active' : ''; }}" href="{{ url('export-list') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Export List</span>
							</a>
						</div>
                        @endif
                        @if (array_search('inout-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['inout-list'])) ? 'active' : ''; }}" href="{{ url('inout-list') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">In Out List</span>
							</a>
						</div>
                        @endif
                    </div>
                </div>
                @endif

                @if($return_result != FALSE)
                    @if (in_array(Auth::user()->role_id, [3,4]))
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['return-diamond-list','add-return-diamond'])) ? 'show' : ''; }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <svg width="23" height="24" viewBox="0 0 23 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 13V13.5C21 16 19 18 16.5 18H5.6V16H16.5C17.9 16 19 14.9 19 13.5V13C19 12.4 19.4 12 20 12C20.6 12 21 12.4 21 13ZM18.4 6H7.5C5 6 3 8 3 10.5V11C3 11.6 3.4 12 4 12C4.6 12 5 11.6 5 11V10.5C5 9.1 6.1 8 7.5 8H18.4V6Z" fill="currentColor"/>
                                        <path opacity="0.3" d="M21.7 6.29999C22.1 6.69999 22.1 7.30001 21.7 7.70001L18.4 11V3L21.7 6.29999ZM2.3 16.3C1.9 16.7 1.9 17.3 2.3 17.7L5.6 21V13L2.3 16.3Z" fill="currentColor"/>
                                    </svg>
                                </span>
                            </span>
                            <span class="menu-title">Return Daimond</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            @if (array_search('return-diamond-list', array_column($permission, 'url')) !== FALSE)
                            <div class="menu-item" aria-haspopup="true">
                                <a class="menu-link {{ (in_array(Request::segment(1), ['return-diamond-list','add-return-diamond'])) ? 'active' : ''; }}" href="{{ url('return-diamond-list') }}">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-title">Return Diamond List</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif

                @if($account_result != FALSE)
                @if (in_array(Auth::user()->role_id, [3,4]))
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['purchase-list','purchase-bill','purchase-bill-form','sales-report'])) ? 'show' : ''; }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M13.0079 2.6L15.7079 7.2L21.0079 8.4C21.9079 8.6 22.3079 9.7 21.7079 10.4L18.1079 14.4L18.6079 19.8C18.7079 20.7 17.7079 21.4 16.9079 21L12.0079 18.8L7.10785 21C6.20785 21.4 5.30786 20.7 5.40786 19.8L5.90786 14.4L2.30785 10.4C1.70785 9.7 2.00786 8.6 3.00786 8.4L8.30785 7.2L11.0079 2.6C11.3079 1.8 12.5079 1.8 13.0079 2.6Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Account</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('purchase-list', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['purchase-list'])) ? 'active' : ''; }}" href="{{ url('purchase-list') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Purchase Report</span>
							</a>
						</div>
                        @endif
                        @if (array_search('purchase-bill', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['purchase-bill','purchase-bill-form'])) ? 'active' : ''; }}" href="{{ url('purchase-bill') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Purchase Bills</span>
							</a>
						</div>
                        @endif
                        @if (array_search('sales-report', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['sales-report'])) ? 'active' : ''; }}" href="{{ url('sales-report') }}">
								<i class="menu-bullet menu-bullet-dot"><span></span></i>
								<span class="menu-title">Sales Report</span>
							</a>
						</div>
                        @endif
                    </div>
                </div>
                @endif
                @endif

                @if($price_result != FALSE)
                @if (in_array(Auth::user()->role_id, [3,4]))
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['pricesetting','shippingpricesetting','price-markup-setting'])) ? 'show' : ''; }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon menu-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 486 486" width="24px" height="24px">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <path d="M243,0C108.8,0,0,108.8,0,243s108.8,243,243,243s243-108.8,243-243S377.2,0,243,0z M312.8,338.8
                                            c-10.6,12.9-24.4,21.6-40.5,26c-7,1.9-10.2,5.6-9.8,12.9c0.3,7.2,0,14.3-0.1,21.5c0,6.4-3.3,9.8-9.6,10
                                            c-4.1,0.1-8.2,0.2-12.3,0.2c-3.6,0-7.2,0-10.8-0.1c-6.8-0.1-10-4-10-10.6c-0.1-5.2-0.1-10.5-0.1-15.7c-0.1-11.6-0.5-12-11.6-13.8 c-14.2-2.3-28.2-5.5-41.2-11.8c-10.2-5-11.3-7.5-8.4-18.3c2.2-8,4.4-16,6.9-23.9c1.8-5.8,3.5-8.4,6.6-8.4c1.8,0,4.1,0.9,7.2,2.5 c14.4,7.5,29.7,11.7,45.8,13.7c2.7,0.3,5.4,0.5,8.1,0.5c7.5,0,14.8-1.4,21.9-4.5c17.9-7.8,20.7-28.5,5.6-40.9 c-5.1-4.2-11-7.3-17.1-10c-15.7-6.9-32-12.1-46.8-21c-24-14.4-39.2-34.1-37.4-63.3c2-33,20.7-53.6,51-64.6 c12.5-4.5,12.6-4.4,12.6-17.4c0-4.4-0.1-8.8,0.1-13.3c0.3-9.8,1.9-11.5,11.7-11.8c1.1,0,2.3,0,3.4,0c1.9,0,3.8,0,5.7,0 c0.8,0,1.6,0,2.3,0c18.6,0,18.6,0.8,18.7,20.9c0.1,14.8,0.1,14.8,14.8,17.1c11.3,1.8,22,5.1,32.4,9.7c5.7,2.5,7.9,6.5,6.1,12.6 c-2.6,9-5.1,18.1-7.9,27c-1.8,5.4-3.5,7.9-6.7,7.9c-1.8,0-4-0.7-6.8-2.1c-14.4-7-29.5-10.4-45.3-10.4c-2,0-4.1,0.1-6.1,0.2 c-4.7,0.3-9.3,0.9-13.7,2.8c-15.6,6.8-18.1,24-4.8,34.6c6.7,5.4,14.4,9.2,22.3,12.5c13.8,5.7,27.6,11.2,40.7,18.4 C330.9,250.9,342.1,303.2,312.8,338.8z" fill="#000"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Pricing</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('pricesetting', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['pricesetting'])) ? 'active' : ''; }}" href="{{ url('pricesetting') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Price Setting</span>
							</a>
						</div>
                        @endif
                    </div>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('price-markup-setting', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['price-markup-setting'])) ? 'active' : ''; }}" href="{{ url('price-markup-setting') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Price Markup Setting</span>
							</a>
						</div>
                        @endif
                    </div>

                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('shippingpricesetting', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['shippingpricesetting'])) ? 'active' : ''; }}" href="{{ url('shippingpricesetting') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Shipping Price Setting</span>
							</a>
						</div>
                        @endif
                    </div>
                </div>
                @endif
                @endif

                @if($log_result != FALSE)
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['login-history-customer','login-history-total','login-history-supplier','login-history-staff','api-history', 'api-history-detail','search-history'])) ? 'show' : ''; }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 3H4C2.89543 3 2 3.89543 2 5V16C2 17.1046 2.89543 18 4 18H4.5C5.05228 18 5.5 18.4477 5.5 19V21.5052C5.5 22.1441 6.21212 22.5253 6.74376 22.1708L11.4885 19.0077C12.4741 18.3506 13.6321 18 14.8167 18H20C21.1046 18 22 17.1046 22 16V5C22 3.89543 21.1046 3 20 3Z" fill="black"></path>
                                    <rect x="6" y="12" width="7" height="2" rx="1" fill="black"></rect>
                                    <rect x="6" y="7" width="12" height="2" rx="1" fill="black"></rect>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Log</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('login-history-customer', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['login-history-customer','login-history-total'])) ? 'active' : ''; }}" href="{{ url('login-history-customer') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Customer History</span>
							</a>
						</div>
                        @endif
                    </div>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('login-history-supplier', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['login-history-supplier'])) ? 'active' : ''; }}" href="{{ url('login-history-supplier') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Supplier History</span>
							</a>
						</div>
                        @endif
                    </div>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('login-history-staff', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['login-history-staff'])) ? 'active' : ''; }}" href="{{ url('login-history-staff') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Staff History</span>
							</a>
						</div>
                        @endif
                    </div>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('api-history', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['api-history'])) ? 'active' : ''; }}" href="{{ url('api-history') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">API Log</span>
							</a>
						</div>
                        @endif
                    </div>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('page-visits', array_column($permission, 'url')) !== FALSE)
                        <div class="menu-item" aria-haspopup="true">
                            <a class="menu-link {{ (in_array(Request::segment(1), ['page-visits'])) ? 'active' : ''; }}" href="{{ url('page-visits') }}">
                                <i class="menu-bullet menu-bullet-dot">
                                <span></span></i>
                                <span class="menu-title">Page Visits</span>
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="menu-sub menu-sub-accordion">
                        @if (array_search('search-history', array_column($permission, 'url')) !== FALSE)
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['search-history'])) ? 'active' : ''; }}" href="{{ url('search-history') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Search History</span>
							</a>
						</div>
                        @endif
                    </div>
                </div>
                @endif

                @if($extra_result != FALSE)
                @if (in_array(Auth::user()->role_id, [3]))
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (in_array(Request::segment(1), ['red-alert','invalid-discount','permission','add-job','manage-job','currency-exchange','daily-reporting','daily-check-list'])) ? 'show' : ''; }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 3H4C2.89543 3 2 3.89543 2 5V16C2 17.1046 2.89543 18 4 18H4.5C5.05228 18 5.5 18.4477 5.5 19V21.5052C5.5 22.1441 6.21212 22.5253 6.74376 22.1708L11.4885 19.0077C12.4741 18.3506 13.6321 18 14.8167 18H20C21.1046 18 22 17.1046 22 16V5C22 3.89543 21.1046 3 20 3Z" fill="black"></path>
                                    <rect x="6" y="12" width="7" height="2" rx="1" fill="black"></rect>
                                    <rect x="6" y="7" width="12" height="2" rx="1" fill="black"></rect>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Extra</span>
                        <span class="menu-arrow"></span>
                    </span>
                    @if (array_search('daily-reporting', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['daily-reporting'])) ? 'active' : ''; }}" href="{{ url('daily-reporting') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Daily Reporting</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('red-alert', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['red-alert'])) ? 'active' : ''; }}" href="{{ url('red-alert') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Red Alert</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('invalid-discount', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['invalid-discount'])) ? 'active' : ''; }}" href="{{ url('invalid-discount') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Invalid Discount</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('permission', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['permission'])) ? 'active' : ''; }}" href="{{ url('permission') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Permission</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('add-job', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['add-job'])) ? 'active' : ''; }}" href="{{ url('add-job') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Add Job</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('manage-job', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['manage-job'])) ? 'active' : ''; }}" href="{{ url('manage-job') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Manage Job</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('manage-job', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['manage-job'])) ? 'active' : ''; }}" href="{{ url('manage-job') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Applied Candidate</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('currency-exchange', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['currency-exchange'])) ? 'active' : ''; }}" href="{{ url('currency-exchange') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Currency Exchange</span>
							</a>
						</div>
                    </div>
                    @endif
                    @if (array_search('daily-check-list', array_column($permission, 'url')) !== FALSE)
                    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item" aria-haspopup="true">
							<a class="menu-link {{ (in_array(Request::segment(1), ['daily-check-list'])) ? 'active' : ''; }}" href="{{ url('daily-check-list') }}">
								<i class="menu-bullet menu-bullet-dot">
								<span></span></i>
								<span class="menu-title">Daily Check List</span>
							</a>
						</div>
                    </div>
                    @endif
                </div>
                @endif
                @endif

                <!-- @ endif -->
                <!-- @ endif -->

                <div class="menu-item" aria-haspopup="true">
                    <a class="menu-link" href="{{ url('logout') }}">
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="4" y="11" width="12" height="2" rx="1" fill="black"/>
                                <path d="M5.86875 11.6927L7.62435 10.2297C8.09457 9.83785 8.12683 9.12683 7.69401 8.69401C7.3043 8.3043 6.67836 8.28591 6.26643 8.65206L3.34084 11.2526C2.89332 11.6504 2.89332 12.3496 3.34084 12.7474L6.26643 15.3479C6.67836 15.7141 7.3043 15.6957 7.69401 15.306C8.12683 14.8732 8.09458 14.1621 7.62435 13.7703L5.86875 12.3073C5.67684 12.1474 5.67684 11.8526 5.86875 11.6927Z" fill="black"/>
                                <path d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z" fill="#C4C4C4"/>
                            </svg>
                        </span>
                        <span class="menu-title">Logout</span>
                    </a>
                </div>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->

    <!--begin::Aside toggler-->
    <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
        <span class="svg-icon svg-icon-2x rotate-180">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
                <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Aside toggler-->
    <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">

    </div>
</div>
<!--end::Aside-->
