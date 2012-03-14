<?xml version="1.0" encoding="ISO-8859-1"?>
<StyledLayerDescriptor version="1.0.0" 
		xsi:schemaLocation="http://www.opengis.net/sld StyledLayerDescriptor.xsd" 
		xmlns="http://www.opengis.net/sld" 
		xmlns:ogc="http://www.opengis.net/ogc" 
		xmlns:xlink="http://www.w3.org/1999/xlink" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<!-- a Named Layer is the basic building block of an SLD document -->
	<NamedLayer>
		<Name>ranh_quan_huyen</Name>
		<UserStyle>
		<!-- Styles can have names, titles and abstracts -->
			<Title>Dinh dang ranh gioi Quan huyen</Title>
			<Abstract>Ranh Quan huyen cua TP Can Tho</Abstract>
			<!-- FeatureTypeStyles describe how to render different features -->
			<!-- A FeatureTypeStyle for rendering polygons -->
			<FeatureTypeStyle>
				<Rule>
					<LineSymbolizer>
						<Stroke>
							<CssParameter name="stroke">#000000</CssParameter>
							<CssParameter name="stroke-width">0.5</CssParameter>
							<CssParameter name="stroke-dasharray">10 10</CssParameter>
						</Stroke>
					</LineSymbolizer>
					<LineSymbolizer>
						<Stroke>
							<GraphicStroke>
								<Graphic>
									<Mark>
										<WellKnownName>circle</WellKnownName>
										<Fill>
											<CssParameter name="fill">#000000</CssParameter>
										</Fill>
									</Mark>
									<Size>2</Size>
								</Graphic>
							</GraphicStroke>
							<CssParameter name="stroke-dasharray">2 18</CssParameter>
							<CssParameter name="stroke-dashoffset">6</CssParameter>
						</Stroke>
					</LineSymbolizer>
				</Rule>
			</FeatureTypeStyle>
		</UserStyle>
	</NamedLayer>
</StyledLayerDescriptor>