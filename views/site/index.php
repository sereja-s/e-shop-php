<?php //include ROOT . '/views/layouts/header.php'; 
?>
<?php include 'views/layouts/header.php'; ?>

<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="left-sidebar">
					<h2>Каталог</h2>
					<div class="panel-group category-products">
						<?php foreach ($categories as $categoryItem) : ?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a href="/category/<?php echo $categoryItem['id']; ?>">
											<?php echo $categoryItem['name']; ?>
										</a>
									</h4>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="col-sm-9 padding-right">

				<!--features_items-->
				<div class="features_items">

					<h2 class="title text-center">новинки</h2>
					<?php foreach ($latestProducts as $product) : ?>

						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">

										<a href="/product/<?php echo $product['id']; ?>">

											<img src=<?php echo models\Product::getImage($product['id']); ?> alt="" />

										</a>

										<h2><?php echo $product['price']; ?>руб.</h2>

										<p>
											<a href="/product/<?php echo $product['id']; ?>">
												<?php echo $product['name']; ?>
											</a>
										</p>

										<a href="#" class="btn btn-default add-to-cart" data-id="<?php echo $product['id']; ?>"><i class="fa fa-shopping-cart"></i>В корзину</a>

									</div>
									<?php if ($product['is_new']) : ?>
										<img src="/template/images/home/new.png" class="new" alt="" />
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!--/features_items-->

				<!--recommended_items-->
				<div class="features_items">

					<h2 class="title text-center">рекомендуемые товары</h2>

					<?php foreach ($sliderProducts as $sliderItem) : ?>

						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">

										<a href="/product/<?php echo $sliderItem['id']; ?>">

											<img src="<?php echo models\Product::getImage($sliderItem['id']); ?>" alt="" />

										</a>
										<h2><?php echo $sliderItem['price']; ?>руб.</h2>
										<p>
											<a href="/product/<?php echo $sliderItem['id']; ?>">
												<?php echo $sliderItem['name']; ?>
											</a>
										</p>
										<a href="#" class="btn btn-default add-to-cart" data-id="<?php echo $sliderItem['id']; ?>"><i class="fa fa-shopping-cart"></i>В корзину</a>
									</div>
									<?php if ($sliderItem['is_new']) : ?>
										<img src="/template/images/home/new.png" class="new" alt="" />
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!--/recommended_items-->


				<!--recommended_items /cycle-slides/-->
				<div class="recommended_items">

					<h2 class="title text-center">Рекомендуемые товары</h2>

					<div class="cycle-slideshow" data-cycle-fx=carousel data-cycle-timeout=5000 data-cycle-carousel-visible=1 data-cycle-carousel-fluid=true data-cycle-slides="div.item" data-cycle-prev="#prev" data-cycle-next="#next">

						<?php foreach ($sliderProducts as $sliderItem) : ?>
							<div class="item">
								<div class="product-image-wrapper">
									<div class="single-products">
										<div class="productinfo text-center">

											<a href="/product/<?php echo $sliderItem['id']; ?>">

												<img src="<?php echo models\Product::getImage($sliderItem['id']); ?>" alt="" />

											</a>

											<h2><?php echo $sliderItem['price']; ?>руб.</h2>
											<a href="/product/<?php echo $sliderItem['id']; ?>">
												<?php echo $sliderItem['name']; ?>
											</a>
											<br /><br />
											<a href="#" class="btn btn-default add-to-cart" data-id="<?php echo $sliderItem['id']; ?>"><i class="fa fa-shopping-cart"></i>В корзину</a>
										</div>
										<?php if ($sliderItem['is_new']) : ?>
											<img src="/template/images/home/new.png" class="new" alt="" />
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<a class="left recommended-item-control" id="prev" href="#recommended-item-carousel" data-slide="prev">
						<i class="fa fa-angle-left"></i>
					</a>
					<a class="right recommended-item-control" id="next" href="#recommended-item-carousel" data-slide="next">
						<i class="fa fa-angle-right"></i>
					</a>
				</div>
			</div>
			<!--/recommended_items /cycle-slides/-->

		</div>
	</div>
	</div>
</section>

<?php //include ROOT . '/views/layouts/footer.php'; 
?>
<?php include 'views/layouts/footer.php'; ?>