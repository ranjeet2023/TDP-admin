@extends('frontend.layouts.app')

@section('meta')
    <title>Best B2B diamonds marketplace for buyers | B2B Diamond Buyers | The Diamond Port</title>
    <meta name="description" content="We are the best B2B diamonds marketplace for buyers, so what are you waiting for, don’t waste any time and start making purchases right away." data-react-helmet="true">
    <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond, B2B diamond marketplace" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{config('app.website')}}" />
    <meta property="og:url" content="https://{{config('app.website')}}/buyer" />
    <meta property="og:site_name" content="{{config('app.website')}}" />
    <link rel="canonical" href="https://www.{{config('app.website')}}/buyer" />
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
 @endsection

 @section('content')
 <!------- Body Section Started -------->
 <div class=" for-buyer">
     <!-- Banner -->
     <section class="main-banner">
         <div class="containerX">
             <div class="row align-items-center align-items-lg-start">
                 <div class="col-12 col-md-6  col-lg-7  text-center text-lg-start p-0 order-2 order-md-1">
                     <div class="banner-content for-buyer-banner">
                         <h1>For Buyers</h1>
                         <h6>The Diamond Port offers a user-friendly interface for buyers looking for an exquisite
                             collection of diamonds at affordable prices. Our platform encourages
                             <span class="text-white">a convenient trading
                                 experience, simplified operability, and innovative customization </span>
                             features for clients. You
                             can enjoy access to a plethora of tools and services on the portal.
                         </h6>
                     </div>
                 </div>

                 <div class="col-12 col-sm-6 col-md-5 col-lg-5 p-0 banner-img  d-flex align-items-end justify-content-center justify-content-lg-end order-1 order-md-2">
                     <img src="{{ asset('assets/frontend/images/for-buyer.png') }}" alt="Diamond" class="img-fluid">
                 </div>

             </div>
         </div>
     </section>

     <!-- Salient Features -->
     <section class="spacer229">
         <div class="row feature-heading">
             <div class="col-12 text-center feature">
                 <h4>Salient Features</h4>
             </div>
         </div>

         <div class="row align-items-center justify-content-center for-buyer-row">
             <div class="col-12 col-sm-5 col-md-5 col-lg-6 p-0 text-center text-sm-start ">
                <img src="{{ asset('assets/frontend/images/huge-inventory-mob.png') }}" alt="Huge Inventory" class="img-fluid feature-img d-lg-none">
                <div class="position-relative d-inline-block d-none d-lg-inline-block">
                    <img src="{{ asset('assets/frontend/images/huge-inventory.png') }}" class="img-fluid" alt="Diverse Inventory Collection">
                    <div class="loupas-3">
                        <img src="{{ asset('assets/frontend/images/diamond.png') }}" alt="Diamond" class="img-fluid">
                     </div>
                 </div>
             </div>
             <div class="col-12 col-sm-7 col-md-5 col-lg-6 feature-content ps-sm-4 p-lg-0">
                 <h5>Huge Inventory </h5>
                 <h6>We offer an array of inventory lists to pick from. We have over 50k+ lab grown and natural diamonds.
                 </h6>
             </div>
         </div>

         <div class="row align-items-center justify-content-center for-buyer-row">
             <div class="col-12 col-sm-7  col-md-5 col-lg-6 feature-content p-0  order-2 order-sm-1">
                 <h5>Fastest Consolidated Shipping</h5>
                 <h6>We offer fastest shipping across the globe. Our efforts are to deliver the stones as soon as the
                     order is completed.</h6>
             </div>
             <div class="col-12 col-sm-5  col-md-5 col-lg-6 text-center text-sm-end p-0 order-1 order-sm-2">
                <img src="{{ asset('assets/frontend/images/consolidated-shipping-mob.png') }}" alt="Fastest Consolidated Shipping" class="img-fluid feature-img d-lg-none">

                <div class="position-relative d-inline-block d-none d-lg-inline-block">
                    <img src="{{ asset('assets/frontend/images/consolidated-shipping.png') }}" alt="Shipment" class="img-fluid">
                    <div class="loupas-2">
                        <img src="{{ asset('assets/frontend/images/dron.png') }}" alt="Shipment" class="img-fluid">
                     </div>
                 </div>
             </div>
         </div>

         <div class="row align-items-center justify-content-center for-buyer-row">
             <div class="col-12 col-sm-5 col-md-5 col-lg-6 p-0  text-center text-sm-start position-relative">

                <img src="{{ asset('assets/frontend/images/stringent-quality-mob.png') }}" alt="Stringent Quality Check" class="img-fluid feature-img d-lg-none">
                <div class="position-relative d-none  d-lg-inline-block ">
                    <img src="{{ asset('assets/frontend/images/stringent-quality.png') }}" alt="Stringent Quality Check" class="img-fluid feature-img">
                    <div class="loupas magnifying">
                        <img src="{{ asset('assets/frontend/images/glass.png') }}" alt="Stringent Quality Check" class="img-fluid feature-img">
                    </div>
                 </div>
             </div>
             <div class="col-12 col-sm-7 col-md-5 col-lg-6 feature-content ps-sm-4 p-lg-0">
                 <h5>Stringent Quality Check</h5>
                 <h6>During the procurement process, our skilled graders inspect each and every stone ordered. You'll
                     never have to be concerned about the quality gain.</h6>
             </div>
         </div>

         <div class="row align-items-center justify-content-center for-buyer-row">
             <div class="col-12 col-sm-7 col-md-5  col-lg-6 feature-content p-0 order-2 order-sm-1">
                 <h5>Manage Order at Place</h5>
                 <h6>Managing orders is now easy with us. Our user-friendly website helps you arrange all orders at
                     one place.</h6>
             </div>
            <div class="col-12 col-sm-5 col-md-5  col-lg-6 text-center text-sm-end p-0 order-1 order-sm-2">
                <img src="{{ asset('assets/frontend/images/manage-order-mob.png') }}" alt="Manage Order at Place" class="img-fluid feature-img d-lg-none">
                <div class="position-relative d-none d-lg-inline-block">
                    <div class="xyz-3">
                        <img src="{{ asset('assets/frontend/images/manage-order.png') }}" alt="Quality check">
                    </div>
                    <div class="loupas-4">
                        <img src="{{ asset('assets/frontend/images/box-1.png') }}" alt="Manage Order at Place" class="img-1">
                        <img src="{{ asset('assets/frontend/images/box-2.png') }}" alt="Manage Order at Place" class="img-2">
                        <img src="{{ asset('assets/frontend/images/box-3.png') }}" alt="Manage Order at Place" class="img-3">
                     </div>
                 </div>
             </div>
         </div>

         <div class="row align-items-center justify-content-center for-buyer-row">
             <div class="col-12 col-sm-5 col-md-5 col-lg-6 p-0 text-center text-sm-start">
                <img src="{{ asset('assets/frontend/images/lowest-pricing-mob.png') }}" alt="Lowest Pricing" class="img-fluid feature-img d-lg-none ">

                 <div class="position-relative d-inline-block d-none d-lg-inline-block LP-div">
                     <svg width="110%" height="304" viewBox="0 0 456 304" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path fill-rule="evenodd" clip-rule="evenodd" d="M352.028 87.9016C281.655 40.1614 195.263 36.5653 126.887 72.377C61.3784 110.675 48.4822 182.72 101.338 234.459L352.028 87.9016Z" fill="url(#paint0_linear_560_1478)" />
                         <path d="M265.869 84.2893L265.829 84.2695H159.597L116.565 111.081L116.486 111.14V111.317V117.122V117.359L212.406 210.23L212.466 210.27H212.822L308.742 117.398L308.802 117.359V117.181V111.258V111.14L265.869 84.2893Z" fill="url(#paint1_linear_560_1478)" />
                         <path d="M308.287 111.199C306.486 111.752 304.209 112.048 301.775 112.048C297.005 112.048 292.927 110.903 292.294 109.363L277.844 92.1672L308.287 111.199Z" fill="url(#paint2_linear_560_1478)" />
                         <path d="M291.937 109.46C291.027 110.941 286.316 112.047 280.813 112.047C275.212 112.047 270.283 110.842 269.61 109.322C269.59 109.282 269.57 109.282 269.531 109.263L277.547 92.2838L291.937 109.46Z" fill="url(#paint3_linear_560_1478)" />
                         <path d="M265.75 84.6063L277.052 91.6743L256.744 84.6063H265.75Z" fill="url(#paint4_linear_560_1478)" />
                         <path d="M277.27 92.1061L269.175 109.263L238.91 94.7122L256.229 84.7617L277.27 92.1061Z" fill="#00ACF5" />
                         <path d="M238.91 95.0685L269.095 109.58C267.69 110.981 261.831 112.028 255.22 112.028C248.193 112.028 242.196 110.883 241.166 109.362C241.186 109.323 241.206 109.284 241.186 109.244L238.91 95.0685Z" fill="url(#paint5_linear_560_1478)" />
                         <path d="M240.909 109.325C240.909 109.325 240.889 109.325 240.889 109.344C239.979 110.884 233.922 112.069 226.796 112.069C220.046 112.069 214.089 110.963 212.842 109.522L238.593 94.9714L240.909 109.325Z" fill="url(#paint6_linear_560_1478)" />
                         <path d="M255.873 84.6063L238.613 94.4975L213.099 84.6063H255.873Z" fill="url(#paint7_linear_560_1478)" />
                         <path d="M238.257 94.7122L238.217 94.7319L238.277 94.7517L212.604 109.243C212.584 109.243 212.584 109.223 212.565 109.223L186.932 94.7714L186.991 94.7517L186.951 94.7122L212.604 84.7617L238.257 94.7122Z" fill="#00ACF5" />
                         <path d="M184.002 109.303C184.002 109.303 183.982 109.303 183.963 109.323C183.052 110.863 176.995 112.048 169.869 112.048C163.041 112.048 157.023 110.922 155.875 109.461L186.259 95.0685L183.963 109.264C184.002 109.284 184.002 109.284 184.002 109.303Z" fill="url(#paint8_linear_560_1478)" />
                         <path d="M184.299 109.305L186.615 94.952L212.307 109.463C211.12 110.924 205.142 112.049 198.333 112.049C191.286 112.049 185.269 110.904 184.279 109.364C184.279 109.345 184.299 109.345 184.299 109.305Z" fill="url(#paint9_linear_560_1478)" />
                         <path d="M212.109 84.6063L186.575 94.4975L169.335 84.6063H212.109Z" fill="url(#paint10_linear_560_1478)" />
                         <path d="M159.676 84.6063H168.444L148.552 91.5361L159.676 84.6063Z" fill="url(#paint11_linear_560_1478)" />
                         <path d="M168.979 84.7617L186.278 94.7122L155.757 109.144L147.918 92.1061L168.979 84.7617Z" fill="#00ACF5" />
                         <path d="M155.519 109.342C154.806 110.862 149.898 112.047 144.316 112.047C138.813 112.047 134.102 110.941 133.192 109.46L147.681 92.2838L155.519 109.342Z" fill="url(#paint12_linear_560_1478)" />
                         <path d="M147.166 92.3842L132.895 109.304L132.915 109.324C132.895 109.343 132.855 109.343 132.855 109.363C132.222 110.883 128.144 112.048 123.374 112.048C120.999 112.048 118.762 111.752 116.981 111.219L147.166 92.3842Z" fill="url(#paint13_linear_560_1478)" />
                         <path d="M116.842 117.24C118.643 116.687 120.939 116.391 123.354 116.391C128.124 116.391 132.202 117.536 132.835 119.076C132.855 119.116 132.895 119.136 132.934 119.136L190.91 188.967L116.842 117.24Z" fill="url(#paint14_linear_560_1478)" />
                         <path d="M133.192 118.958C134.142 117.497 138.833 116.391 144.296 116.391C149.898 116.391 154.826 117.596 155.519 119.116L192.038 189.816L133.192 118.958Z" fill="url(#paint15_linear_560_1478)" />
                         <path d="M184.121 119.175C184.061 119.175 184.002 119.155 183.982 119.116C183.072 117.576 177.015 116.391 169.889 116.391C163.021 116.391 156.964 117.536 155.875 119.017L200.075 170.369L184.121 119.175Z" fill="url(#paint16_linear_560_1478)" />
                         <path d="M184.378 118.938C185.625 117.477 191.484 116.391 198.333 116.391C205.3 116.391 211.436 117.576 212.387 119.076L200.332 170.152L184.378 118.938Z" fill="url(#paint17_linear_560_1478)" />
                         <path d="M192.969 190.96L156.37 120.083L200.075 170.842C200.114 170.901 200.174 170.901 200.233 170.881L212.327 209.696L192.969 190.96Z" fill="url(#paint18_linear_560_1478)" />
                         <path d="M212.624 209.44L200.53 170.684L212.564 119.668L224.718 170.822L212.624 209.44Z" fill="url(#paint19_linear_560_1478)" />
                         <path d="M269.234 119.017C268.165 117.536 262.108 116.391 255.22 116.391C248.213 116.391 242.255 117.517 241.186 119.037L225.272 170.092L269.234 119.017Z" fill="url(#paint20_linear_560_1478)" />
                         <path d="M224.896 170.289L212.723 119.076C213.713 117.576 219.809 116.411 226.776 116.411C233.863 116.411 239.88 117.576 240.85 119.096L224.896 170.289Z" fill="url(#paint21_linear_560_1478)" />
                         <path d="M232.299 190.98L212.941 209.716L225.054 170.862V170.842L268.759 120.083L232.299 190.96V190.98Z" fill="url(#paint22_linear_560_1478)" />
                         <path d="M269.63 119.057C269.63 119.057 269.63 119.037 269.63 119.057C270.441 117.556 275.271 116.391 280.794 116.391C286.296 116.391 291.007 117.497 291.918 118.978L233.229 189.835L269.63 119.057Z" fill="url(#paint23_linear_560_1478)" />
                         <path d="M234.298 189.046L292.254 119.096C292.254 119.076 292.274 119.096 292.294 119.076C292.927 117.556 297.005 116.391 301.775 116.391C304.269 116.391 306.624 116.707 308.426 117.28L234.298 189.046Z" fill="url(#paint24_linear_560_1478)" />
                         <path d="M308.485 116.964C306.664 116.391 304.269 116.075 301.755 116.075C297.044 116.075 293.085 117.161 292.115 118.701C290.987 117.161 286.336 116.075 280.793 116.075C275.152 116.075 270.481 117.2 269.432 118.76C268.125 117.2 262.286 116.075 255.22 116.075C248.154 116.075 242.295 117.2 241.008 118.76C239.702 117.2 233.843 116.075 226.796 116.075C219.69 116.075 213.831 117.2 212.564 118.78C211.298 117.2 205.439 116.075 198.333 116.075C191.266 116.075 185.408 117.2 184.121 118.76C182.815 117.2 176.956 116.075 169.909 116.075C162.843 116.075 156.984 117.2 155.697 118.76C154.648 117.2 149.977 116.075 144.336 116.075C138.793 116.075 134.162 117.161 133.014 118.701C132.044 117.161 128.085 116.075 123.374 116.075C120.939 116.075 118.623 116.371 116.822 116.924V111.554C118.643 112.107 120.959 112.403 123.374 112.403C128.085 112.403 132.044 111.317 133.014 109.777C134.142 111.317 138.793 112.403 144.336 112.403C149.977 112.403 154.648 111.278 155.697 109.718C157.004 111.278 162.843 112.403 169.909 112.403C176.975 112.403 182.834 111.278 184.121 109.718C185.427 111.278 191.286 112.403 198.333 112.403C205.439 112.403 211.298 111.278 212.564 109.698C213.831 111.278 219.69 112.403 226.796 112.403C233.863 112.403 239.721 111.278 241.008 109.718C242.314 111.278 248.173 112.403 255.22 112.403C262.286 112.403 268.145 111.278 269.432 109.718C270.481 111.278 275.152 112.403 280.793 112.403C286.336 112.403 290.987 111.317 292.115 109.777C293.085 111.317 297.044 112.403 301.755 112.403C304.269 112.403 306.664 112.087 308.485 111.514V116.964V116.964Z" fill="url(#paint25_linear_560_1478)" />
                         <g clip-path="url(#clip0_560_1478)">
                             <path d="M366.367 255.647L321.585 153.534L314.342 151.12L357.87 250.376L359.124 253.233L366.367 255.647Z" fill="url(#paint26_linear_560_1478)" />
                             <path d="M325.294 100.333C325.336 99.7239 325.534 99.1596 325.873 98.6779C326.213 98.1963 326.686 97.8083 327.262 97.5395C327.838 97.2707 328.503 97.1273 329.214 97.1187C329.926 97.1101 330.666 97.2366 331.387 97.4897C331.387 97.4897 326.131 95.7171 324.042 94.8755C321.624 93.9105 318.395 95.1723 318.107 99.1171L314.342 151.12L321.585 153.534L325.294 100.333Z" fill="url(#paint27_linear_560_1478)" />
                             <path d="M355.122 183.412C356.851 181.951 356.664 178.803 354.878 174.729C353.27 171.064 351.291 168.842 349.378 170.444C347.543 171.978 347.957 175.579 349.541 179.191C351.189 182.969 353.319 184.91 355.122 183.412Z" fill="#39A8DF" />
                             <path d="M380.77 182.465C382.499 181.004 382.313 177.856 380.528 173.786C378.919 170.117 376.982 167.863 375.069 169.464C373.156 171.066 373.646 174.604 375.228 178.212C376.836 182.031 378.965 183.967 380.77 182.465Z" fill="#39A8DF" />
                             <path d="M330.617 111.867C332.322 115.755 336.205 118.547 339.293 118.099C342.38 117.651 343.492 114.135 341.787 110.247C340.082 106.359 336.22 103.573 333.101 104.025C329.983 104.477 328.913 107.983 330.617 111.867Z" fill="white" />
                             <path d="M345.026 183.266C347.998 190.045 352.976 191.498 357.001 188.108C360.84 184.92 362.608 178.029 359.294 170.471C356.582 164.286 352.019 161.878 347.456 165.706C342.894 169.534 342.197 176.818 345.026 183.266ZM349.355 170.447C351.272 168.854 353.248 171.067 354.855 174.733C356.635 178.79 356.85 181.951 355.099 183.415C353.348 184.879 351.175 182.971 349.519 179.194C347.945 175.572 347.534 171.979 349.369 170.446L349.355 170.447Z" fill="#39A8DF" />
                             <path d="M370.67 182.32C373.682 189.07 378.665 190.523 382.659 187.181C386.475 183.997 388.261 177.103 384.947 169.545C382.235 163.36 377.672 160.952 373.113 164.788C368.554 168.624 367.843 175.875 370.67 182.32ZM375.062 169.448C376.979 167.854 378.911 170.1 380.521 173.77C382.3 177.827 382.514 180.984 380.763 182.448C379.012 183.912 376.842 182.012 375.222 178.2C373.632 174.606 373.185 171.036 375.062 169.448Z" fill="#39A8DF" />
                             <path fill-rule="evenodd" clip-rule="evenodd" d="M321.579 153.521L325.293 100.333C325.336 99.7232 325.534 99.1589 325.873 98.6773C326.213 98.1956 326.686 97.8076 327.262 97.5388C327.838 97.27 328.503 97.1266 329.214 97.118C329.925 97.1094 330.666 97.2359 331.387 97.489L367.839 110.306L412.616 212.407L366.361 255.634L321.579 153.521ZM339.279 118.1C342.362 117.653 343.479 114.137 341.774 110.248L341.787 110.246C340.084 106.363 336.19 103.568 333.102 104.016C330.015 104.463 328.898 107.98 330.603 111.868C332.308 115.756 336.196 118.547 339.279 118.1Z" fill="url(#paint28_linear_560_1478)" />
                         </g>
                         <g clip-path="url(#clip0_560_1478)" id="PriceLabel">

                             <path d="M345.343 135.93L347.101 139.842L345.817 141.131L342.988 134.837L351.063 126.735L352.133 129.116L345.343 135.93Z" fill="white" />
                             <path d="M348.894 148.237C348.394 147.125 348.113 145.927 348.05 144.641C347.987 143.355 348.145 142.127 348.525 140.955C348.913 139.777 349.504 138.789 350.298 137.992C351.085 137.202 351.913 136.766 352.782 136.684C353.659 136.594 354.484 136.836 355.257 137.41C356.03 137.984 356.666 138.826 357.165 139.938C357.67 141.06 357.951 142.259 358.009 143.533C358.073 144.819 357.908 146.046 357.515 147.213C357.135 148.385 356.552 149.365 355.765 150.154C354.971 150.951 354.135 151.395 353.258 151.485C352.394 151.579 351.575 151.339 350.802 150.765C350.03 150.191 349.394 149.348 348.894 148.237ZM350.34 146.786C350.661 147.5 351.052 148.021 351.513 148.349C351.981 148.669 352.488 148.777 353.035 148.673C353.581 148.569 354.124 148.246 354.664 147.705C355.204 147.163 355.605 146.538 355.869 145.829C356.14 145.113 356.261 144.374 356.232 143.613C356.204 142.852 356.029 142.114 355.708 141.4C355.387 140.686 354.993 140.159 354.527 139.82C354.067 139.492 353.563 139.38 353.017 139.484C352.478 139.581 351.939 139.899 351.399 140.441C350.859 140.983 350.454 141.612 350.183 142.328C349.912 143.044 349.787 143.787 349.808 144.556C349.841 145.328 350.019 146.071 350.34 146.786Z" fill="white" />
                             <path d="M367.709 163.769L358.144 168.554L356.882 165.747L361.63 157.75L354.833 161.188L353.567 158.41L360.216 147.099L361.363 149.65L356.034 158.034L363.389 154.158L364.582 156.811L359.371 165.347L366.555 161.2L367.709 163.769Z" fill="white" />
                             <path d="M371.623 180.542C371.191 180.976 370.718 181.229 370.202 181.302C369.692 181.386 369.175 181.221 368.654 180.807C368.137 180.404 367.659 179.715 367.221 178.74L366.326 176.75L363.226 179.86L362.155 177.479L370.23 169.377L372.195 173.749C372.608 174.667 372.852 175.558 372.926 176.421C373.001 177.284 372.921 178.064 372.686 178.761C372.455 179.469 372.101 180.063 371.623 180.542ZM368.482 177.326C368.778 177.984 369.097 178.371 369.44 178.487C369.791 178.596 370.152 178.464 370.522 178.093C371.309 177.304 371.412 176.263 370.83 174.97L369.982 173.082L367.633 175.438L368.482 177.326Z" fill="white" />
                             <path d="M369.56 193.952L371.529 188.175L371.024 187.052L367.878 190.209L366.807 187.828L374.882 179.726L376.885 184.183C377.298 185.101 377.538 185.996 377.605 186.867C377.679 187.73 377.599 188.51 377.364 189.206C377.142 189.907 376.799 190.489 376.336 190.953C375.804 191.487 375.22 191.744 374.584 191.724C373.956 191.696 373.36 191.315 372.795 190.582L370.798 196.707L369.56 193.952ZM372.239 185.833L373.134 187.824C373.424 188.47 373.745 188.848 374.096 188.956C374.454 189.057 374.823 188.918 375.201 188.539C375.571 188.167 375.785 187.722 375.843 187.203C375.908 186.677 375.796 186.091 375.505 185.444L374.61 183.454L372.239 185.833Z" fill="white" />
                             <path d="M380.851 193.005L372.776 201.107L371.706 198.725L379.78 190.623L380.851 193.005Z" fill="white" />
                             <path d="M377.622 198.819C378.417 198.022 379.244 197.571 380.103 197.465C380.975 197.364 381.788 197.593 382.543 198.152C383.311 198.714 383.949 199.562 384.459 200.696C385.055 202.023 385.346 203.418 385.332 204.88C385.318 206.342 384.992 207.682 384.352 208.899L383.121 206.16C383.385 205.501 383.501 204.826 383.469 204.133C383.443 203.452 383.284 202.788 382.994 202.142C382.683 201.45 382.293 200.944 381.825 200.624C381.369 200.308 380.872 200.198 380.334 200.294C379.8 200.402 379.263 200.726 378.723 201.268C378.191 201.802 377.787 202.422 377.511 203.127C377.248 203.835 377.124 204.568 377.14 205.325C377.168 206.086 377.338 206.813 377.649 207.505C377.94 208.151 378.291 208.621 378.703 208.915C379.128 209.213 379.594 209.305 380.102 209.19L381.333 211.929C380.33 212.36 379.372 212.308 378.461 211.775C377.562 211.245 376.812 210.311 376.211 208.973C375.701 207.839 375.413 206.638 375.347 205.371C375.294 204.108 375.461 202.897 375.846 201.737C376.236 200.588 376.828 199.616 377.622 198.819Z" fill="white" />
                             <path d="M387.54 212.108L385.516 214.139L387.313 218.136L386.029 219.425L384.232 215.427L382.092 217.575L384.118 222.082L382.799 223.406L379.702 216.516L387.789 208.403L390.885 215.292L389.566 216.615L387.54 212.108Z" fill="white" />


                         </g>

                         <defs>
                             <linearGradient id="paint0_linear_560_1478" x1="529.781" y1="-72.1399" x2="178.744" y2="324.204" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#00ACF5" />
                                 <stop offset="1" stop-color="#00ACF5" stop-opacity="0" />
                             </linearGradient>
                             <linearGradient id="paint1_linear_560_1478" x1="312.674" y1="94.5552" x2="146.41" y2="126.342" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint2_linear_560_1478" x1="273.952" y1="80.4124" x2="310.634" y2="103.59" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint3_linear_560_1478" x1="273.975" y1="89.064" x2="315.782" y2="96.5389" gradientUnits="userSpaceOnUse">
                                 <stop offset="0.00109459" stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint4_linear_560_1478" x1="266.82" y1="82.1799" x2="271.382" y2="93.519" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint5_linear_560_1478" x1="253.886" y1="89.2467" x2="268.326" y2="111.48" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint6_linear_560_1478" x1="218.409" y1="92.1858" x2="269.058" y2="105.298" gradientUnits="userSpaceOnUse">
                                 <stop offset="0.00109459" stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint7_linear_560_1478" x1="234.32" y1="81.2108" x2="238.92" y2="98.4181" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint8_linear_560_1478" x1="161.902" y1="92.3023" x2="216.089" y2="107.594" gradientUnits="userSpaceOnUse">
                                 <stop offset="0.00109459" stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint9_linear_560_1478" x1="198.185" y1="89.0827" x2="213.194" y2="110.367" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint10_linear_560_1478" x1="190.556" y1="81.2108" x2="195.156" y2="98.4181" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint11_linear_560_1478" x1="158.421" y1="82.2274" x2="162.897" y2="93.3418" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint12_linear_560_1478" x1="144.269" y1="85.4995" x2="162.682" y2="103.495" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint13_linear_560_1478" x1="131.956" y1="85.6338" x2="149.57" y2="109.023" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint14_linear_560_1478" x1="124.23" y1="116.411" x2="238.743" y2="135.878" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint15_linear_560_1478" x1="162.387" y1="91.1859" x2="226.47" y2="135.615" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint16_linear_560_1478" x1="177.804" y1="97.8617" x2="225.26" y2="131.477" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint17_linear_560_1478" x1="203.609" y1="109.34" x2="251.745" y2="136.329" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint18_linear_560_1478" x1="167.469" y1="105.483" x2="274.178" y2="115.991" gradientUnits="userSpaceOnUse">
                                 <stop offset="0.00109459" stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint19_linear_560_1478" x1="212.53" y1="88.8511" x2="249.521" y2="97.473" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint20_linear_560_1478" x1="247.083" y1="97.9565" x2="294.292" y2="131.389" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint21_linear_560_1478" x1="226.677" y1="97.915" x2="264.347" y2="114.926" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint22_linear_560_1478" x1="268.79" y1="115.768" x2="315.475" y2="157.163" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint23_linear_560_1478" x1="262.346" y1="91.1791" x2="326.379" y2="135.442" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint24_linear_560_1478" x1="308.802" y1="120.268" x2="336.922" y2="197.539" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint25_linear_560_1478" x1="211.911" y1="106.58" x2="216.118" y2="131.02" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#00ACF5" />
                                 <stop offset="1" stop-color="#172568" />
                             </linearGradient>
                             <linearGradient id="paint26_linear_560_1478" x1="322.652" y1="155.896" x2="340.825" y2="150.738" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#172568" />
                                 <stop offset="1" stop-color="#00ACF5" />
                             </linearGradient>
                             <linearGradient id="paint27_linear_560_1478" x1="322.486" y1="80.7693" x2="345.779" y2="149.268" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#00ACF5" />
                                 <stop offset="1" stop-color="#172568" />
                             </linearGradient>
                             <linearGradient id="paint28_linear_560_1478" x1="390.986" y1="234.27" x2="359.799" y2="64.6617" gradientUnits="userSpaceOnUse">
                                 <stop stop-color="#39A8DF" />
                                 <stop offset="1" stop-color="#172568" />
                             </linearGradient>
                             <clipPath id="clip0_560_1478">
                                 <rect width="67.8344" height="171.97" fill="white" transform="matrix(0.989651 -0.143493 0.401628 0.915803 291.486 99.0039)" />
                             </clipPath>
                         </defs>
                     </svg>

                     <svg width="110%" height="304" viewBox="0 0 456 304" fill="none" class="lowPrice">
                         <path d="M334.986 110.269C305.986 150.203 184.486 167.769 126.522 150.203C122.201 147.058 120.986 144.269 138.486 138.269" stroke="#39A8DF" stroke-width="2" stroke-dasharray="4 4" />
                         <path d="M285.986 96.2695C305.486 100.27 341.186 107.47 333.986 110.27" stroke="#39A8DF" stroke-width="2" stroke-dasharray="4 4" />
                     </svg>
                 </div>
             </div>
             <div class="col-12 col-sm-7 col-md-5 col-lg-6 feature-content ps-sm-4 p-lg-0">
                 <h5>Lowest Pricing</h5>
                 <h6>We operate with the smallest possible margins and guarantee the lowest prices for our goods when
                     compared to other B2B websites.</h6>
             </div>
         </div>

         <div class="row align-items-center justify-content-center for-buyer-row">
             <div class="col-12 col-sm-7 col-md-5 col-lg-6 feature-content p-0 order-2 order-sm-1">
                 <h5>24*7 Customer Support</h5>
                 <h6>Our customer support representatives are available 24 hours a day, 7 days a week.</h6>
             </div>
             <div class="col-12 col-sm-5 col-md-5 col-lg-6 text-center text-sm-end p-0 order-1 order-sm-2">
                <img src="{{ asset('assets/frontend/images/customer-support-mob.png') }}" alt="Customer support" class="img-fluid feature-img d-lg-none">


                <div class="position-relative d-none d-lg-inline-block">
                    <div class="xyz-3">
                        <img src="{{ asset('assets/frontend/images/customer-support.png') }}" alt="Customer support">
                     </div>
                     <div class="loupas-4">
                         <img src="{{ asset('assets/frontend/images/tag.png') }}" alt="Customer support" class="tagimage">

                     </div>
                 </div>
             </div>
         </div>
     </section>

     <div class="SectionLine"></div>
     <!-- Video -->
     <section>
         <div class="containerX LandingPage">
             <div class="row">
                 <div class="col-12 video-div p-0">
                     <div class="d-block position-relative videoback">
                         <video loop>
                                 <source src="{{ asset('assets/frontend/images/diamond-video.mp4') }}" />
                         </video>
                         <button class="video-control">
                             <span class="video-control-play">
                                 <span class="video-control-symbol" aria-hidden="true"><i class="ri-play-fill"></i></span>
                             </span>
                             <span class="video-control-pause">
                                 <span class="video-control-symbol" aria-hidden="true"><i class="ri-pause-fill"></i></span>
                             </span>
                         </button>
                     </div>
                 </div>
             </div>
         </div>
     </section>
     <div class="SectionLine"></div>

     <section>
         <div class="row">
             <div class="col-12 text-center feature">
                 <h4>Frequently Asked Questions</h4>
             </div>
         </div>
     </section>

     <section class="containerX ">
         <div class="row question-accordion">
             <div class="col-12 p-0">
                 <div class="accordion" id="accordionExample">
                     <div class="accordion-item mt-0">
                         <h6 class="accordion-header" id="headingOne">
                             <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                 How to register on The Diamond Port?
                             </button>
                         </h6>
                         <div id="collapseOne" class="accordion-collapse collapse  show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                             <div class="accordion-body">
                                Open the The Diamond Port app and tap create account. Enter your bussiness information and submit. after that check your email for verfication email. onece your email verified then we process for your account apporval.
                             </div>
                         </div>
                     </div>

                     <div class="accordion-item">
                         <h6 class="accordion-header" id="headingtwo">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsetwo" aria-expanded="false" aria-controls="collapsetwo">
                                 Are there any subscription charges?
                             </button>
                         </h6>
                         <div id="collapsetwo" class="accordion-collapse collapse" aria-labelledby="headingtwo" data-bs-parent="#accordionExample">
                             <div class="accordion-body">
                                All our services are free of cost.
                             </div>
                         </div>
                     </div>

                     <div class="accordion-item">
                         <h6 class="accordion-header" id="headingthree">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsethree" aria-expanded="false" aria-controls="collapsethree">
                                 Does the website have an API that allows inventory to be shared between platforms?
                             </button>
                         </h6>
                         <div id="collapsethree" class="accordion-collapse collapse" aria-labelledby="headingthree" data-bs-parent="#accordionExample">
                             <div class="accordion-body">
                                Yes, with the suppliers' consent, we exchange inventory on other sites via API.
                             </div>
                         </div>
                     </div>

                     <div class="accordion-item">
                         <h6 class="accordion-header" id="headingfour">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
                                 Is it possible to see the details of the provider before placing an order?
                             </button>
                         </h6>
                         <div id="collapsefour" class="accordion-collapse collapse" aria-labelledby="headingfour" data-bs-parent="#accordionExample">
                             <div class="accordion-body">
                                We want the Diamonds to sell on their own merits, not because of the supplier's name. Thus, the supplier's name is not displayed.
                             </div>
                         </div>
                     </div>

                     <div class="accordion-item">
                         <h6 class="accordion-header" id="headingfive">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefive" aria-expanded="false" aria-controls="collapsefive">
                                 Is it possible to view the website from a mobile phone?
                             </button>
                         </h6>
                         <div id="collapsefive" class="accordion-collapse collapse" aria-labelledby="headingfive" data-bs-parent="#accordionExample">
                             <div class="accordion-body">
                                Yes, our website is mobile-friendly, and we also offer Google and iOS apps.
                             </div>
                         </div>
                     </div>

                     <div class="accordion-item">
                         <h6 class="accordion-header" id="headingsix">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsesix" aria-expanded="false" aria-controls="collapsesix">
                                 How long does it take for diamonds to be delivered?
                             </button>
                         </h6>
                         <div id="collapsesix" class="accordion-collapse collapse" aria-labelledby="headingsix" data-bs-parent="#accordionExample">
                             <div class="accordion-body">
                                As we have daily shipments from India, Hong Kong, and other locations, it takes 5-7 days to deliver once the order is received and the customer's Q.C is approved.
                             </div>
                         </div>
                     </div>

                     <div class="accordion-item">
                         <h6 class="accordion-header" id="headingseven">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                                 How can I purchase diamonds that aren't featured on the website?
                             </button>
                         </h6>
                         <div id="collapseseven" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                             <div class="accordion-body">
                                Simply share the certificate number of natural or lab-grown diamonds with our support team, and they will locate the diamond(s) for you.
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <!-- Book your appointment to know more! -->
     <div class="LandingPage">
         <section class="Bookappoinment">
             <div class="containerX">
                 <div class="row">
                     <div class="col-12 Appoinment-content">
                         <h5>Book your appointment to know more!</h5>
                         <a href="https://calendly.com/thediamondport" target="_blank" class="theme-btn BookNow">
                             Book Now
                         </a>
                     </div>
                 </div>
             </div>
         </section>
     </div>
 </div>
 @endsection
