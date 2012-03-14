<?xml version="1.0" encoding="ISO-8859-1"?>
<StyledLayerDescriptor version="1.0.0" 
		xsi:schemaLocation="http://www.opengis.net/sld StyledLayerDescriptor.xsd" 
		xmlns="http://www.opengis.net/sld" 
		xmlns:ogc="http://www.opengis.net/ogc" 
		xmlns:xlink="http://www.w3.org/1999/xlink" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<!-- a Named Layer is the basic building block of an SLD document -->
	<NamedLayer>
		<Name>name_of_style</Name>
		<UserStyle>
		<!-- Styles can have names, titles and abstracts -->
			<Title>Name of style</Title>
			<Abstract>A template of SLD file</Abstract>
			<!-- FeatureTypeStyles describe how to render different features -->
			<!-- A FeatureTypeStyle for rendering polygons -->
			<FeatureTypeStyle>
				<Rule>
					<Name>rule1</Name>
					<Title>Title of rule 1</Title>
					<Abstract>Abstract of rule 1</Abstract>
					<!-- Define rule 1 here -->
				</Rule>
			</FeatureTypeStyle>
		</UserStyle>
	</NamedLayer>
</StyledLayerDescriptor>