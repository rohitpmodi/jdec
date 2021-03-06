@extends('frontend/layout/layout')

@section('htmlschema')
itemscope itemtype="http://schema.org/LocalBusiness
@endsection

@section('seo')
@endsection

@section('json-ld')
@endsection

@section('goodrelations-off')
@endsection

@section('title')
Jeevandeep Prakashan Pvt. Ltd.
@endsection

@section('bodyschema')@endsection
@section('bodytag')@endsection

@section('header_styles')@endsection


@section('content')
<!-- Start Banner -->
<div class="banner-store">
	<div class="wrapper cf">
		<div class="banner-store-text">
			<h3>PURCHASE Your School's books online</h3>
            <a href="{!! url(getLang() . '/school') !!}" class="shop-online"><i class="fa fa-link" style="margin-right: 10px;"></i> SHOP ONLINE</a>
            <p class="available">*Available only for select School's and Standards.</p>
            <p class="fontI">Click 'Shop Online' to check if your School is listed. If your School is not listed, you can contact us at enquiries@jeevandeep.in.</p>
		</div>
	</div>
</div>
<!-- End Banner -->
<!-- Start Jeevandeep -->
<div class="jeevandeep-online">
	<div class="wrapper cf">The Jeevandeep Online Book Store has been developed for the convenience of Parents and Guardians. You can now purchase books online by selecting your child’s school and standard with the convenience of secure and safe online transactions. Your purchases will be shipped to your given address by Jeevandeep. You will also be able to track your orders online and download invoices when you wish to. So go ahead and experience the Jeevandeep Online Store!</div>
</div>
<!-- End Jeevandeep -->
@endsection

@section('footer_scripts')@endsection
@section('pp_footer_scripts')@endsection
@section('inlinejs')@endsection
