<html>
	<head>
		<link rel="stylesheet" type="text/css" href="assets/css/main.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</head>
	<body class="body">
		<input type="hidden" id="imageId" value="<?php echo $this->image->imageId; ?>" />
		<input type="hidden" id="nextImage" value="<?php echo $this->image->getNextId(); ?>" />
		<input type="hidden" id="prevImage" value="<?php echo $this->image->getPrevId(); ?>" />

		<div class="mainContainer">
			<div class="content">
				<div class="topbar"></div>
				<div class="imageContainer">
					<div class="imageElement"><a href="javascript:void(0)" class="nav prev"></a></div>
					<div class="imageElement" id="imageLanding"><img src="<?php echo $this->image->path;?>" id="imageArea" /></div>
					<div class="imageElement"><a href="javascript:void(0)" class="nav next"></a></div>
				</div>
				<div class="voteContainer">
					<div class="voteYes vote">
						<div class="voteElement"><span class="yesPercent"><?php echo $this->image->getYesVotePercentage(); ?>%</span></div>
						<div class="voteElement"><a href="javascript:void(0)" class="voteButton voteYesButton"></a></div>
					</div>
					<div class="voteNo vote">
						<div class="voteElement"><a href="javascript:void(0)" class="voteButton voteNoButton"></a></div>
						<div class="voteElement"><span class="noPercent"><?php echo $this->image->getNoVotePercentage(); ?>%</span></div>
					</div>
					<br class="clearfix" />
				</div>
			</div>
		</div>
		<script type="text/javascript" src="assets/js/main.js"></script>
	</body>
</html>
