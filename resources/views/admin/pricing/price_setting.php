<!DOCTYPE html>
<html lang="en">
<head>
	<title>The Diamond Port</title>
	<meta charset="utf-8" />
	<meta name="description" content="thediamondport.com" />
	<meta name="keywords" content="thediamondport.com" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="favicon.ico" />

	<link href="<?= base_url() ?>massets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
	<?php $this->load->view('admin/all_css.php'); ?>
	
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			<?php $this->load->view('admin/new_sidebar.php'); ?>
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<?php $this->load->view('admin/new_header.php'); ?>
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<div id="kt_content_container" class="container-xxl">
						<div id="success"></div>
						<?php if ($this->session->flashdata('SUCCESSMSG')) { ?>
							<div role="alert" class="alert alert-dismissible alert-success d-flex flex-column flex-sm-row p-3">
								<h4 class="m-2 text-success"><?= $this->session->flashdata('SUCCESSMSG'); $this->session->set_flashdata('SUCCESSMSG', ''); ?></h4>
								<button type="button" class="position-absolute position-sm-relative m-sm-0 top-0 end-0 btn btn-sm  btn-icon ms-sm-auto" data-bs-dismiss="alert"><i class="fa fa-times"></i></button>
							</div>
						<?php } ?>
						<?php if (validation_errors()) { ?>
							<div id="errormessage">
								<div role="alert" class="alert alert-danger"><?php echo validation_errors(); ?></div>
							</div>
						<?php } ?>
						<div class="card card-custom gutter-b">
							<div class="card-header">
								<h3 class="card-title"><?php echo $Supplier[0]->supplier_name; ?>  Price</h3>
							</div>
							<!--begin: Datatable-->
							<div class="card-body">
								<form id="addclient" class="form-horizontal form-label-left" method="post" >
									<div class="row">
										<div class="col-md-6 col-sm-12 col-xs-12">
											<div class="x_content">
												<div class="x_title">
													<h2>Round</h2>
													<div class="clearfix"></div>
												</div>
												<?php foreach ($round as $value) { ?>

													<div class="form-group">
														<label class="control-label col-md-2 col-sm-2 col-xs-12" for="01822"><?php echo $value->min_range . "-" . $value->max_range; ?><span style="color: #ff3333;">*</span></label>
														<div class="col-md-6 col-sm-6 col-xs-12">
															<?php
															if (array_key_exists('price_id', (array)$value)) {
																$name = $value->price_id;
															} else {
																$name = $value->setting_id;
															}
															?>
															<input type="text" id="last-name" value="<?php echo $value->pricechange ?>" name="<?php echo $name; ?>" required="required" class="form-control col-md-7 col-xs-12">
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
										<div class="col-md-6 col-sm-12 col-xs-12">
											<div class="x_content">
												<div class="x_title">
													<h2>Pear</h2>
													<div class="clearfix"></div>
												</div>
												<?php foreach ($pear as $data) { ?>
													<?php
													if (array_key_exists('price_id', (array)$data)) {
														$pear = $data->price_id;
													} else {
														$pear = $data->setting_id;
													}
													?>
													<div class="form-group">
														<label class="control-label col-md-2 col-sm-2 col-xs-12" for="01822"><?php echo $data->min_range . "-" . $data->max_range; ?><span style="color: #ff3333;">*</span></label>
														<div class="col-md-6 col-sm-6 col-xs-12">
															<input type="text" id="last-name" value="<?php echo $data->pricechange ?>" name="<?php echo $pear; ?>" required="required" class="form-control col-md-7 col-xs-12">
														</div>
													</div>
												<?php } ?>
											</div>
										</div>

										<div class="form-group"></div>
										<div class="ln_solid"></div>
										<div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-2">
											<button class="btn btn-success Save" type="submit" name="submit" value="submit">Save</button>
											<button class="btn btn-success Save" type="submit" name="update" value="submit">Save & Update</button>
											<a class="btn btn-danger" href="<?= base_url(); ?>supplier-list">Back</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view('admin/new_footer'); ?>
		</div>
	</div>
	<div class="modal fade" id="header-modal" aria-hidden="true"></div>

	<script>var hostUrl = "/massets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="<?= base_url() ?>massets/plugins/global/plugins.bundle.js"></script>
	<script src="<?= base_url() ?>massets/js/scripts.bundle.js"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="<?= base_url() ?>massets/js/custom/intro.js"></script>
	<!--end::Page Custom Javascript-->
	</body>
</html>
