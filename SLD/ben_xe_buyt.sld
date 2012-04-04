<?xml version="1.0" encoding="ISO-8859-1"?>
<StyledLayerDescriptor version="1.0.0" 
		xsi:schemaLocation="http://www.opengis.net/sld StyledLayerDescriptor.xsd" 
		xmlns="http://www.opengis.net/sld" 
		xmlns:ogc="http://www.opengis.net/ogc" 
		xmlns:xlink="http://www.w3.org/1999/xlink" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<!-- a Named Layer is the basic building block of an SLD document -->
	<NamedLayer>
		<Name>ben_xe_buyt</Name>
		<UserStyle>
		<!-- Styles can have names, titles and abstracts -->
			<Title>Ben xe buyt</Title>
			<Abstract>Trinh bay cac ben xe buyt duoi dang bieu tuong</Abstract>
			<!-- FeatureTypeStyles describe how to render different features -->
			<!-- A FeatureTypeStyle for rendering polygons -->
			<FeatureTypeStyle>
				<Rule>
					<MaxScaleDenominator>120000</MaxScaleDenominator>
					<PointSymbolizer>
						<Graphic>
							<ExternalGraphic>
								<OnlineResource xlink:type="simple" xlink:href="images/station.png" />
								<Format>image/png</Format>
							</ExternalGraphic>
							<Size>14</Size>
						</Graphic>
					</PointSymbolizer>
				</Rule>
				<Rule>
					<MaxScaleDenominator>30000</MaxScaleDenominator>
					<PointSymbolizer>
						<Graphic>
							<ExternalGraphic>
								<OnlineResource xlink:type="simple" xlink:href="images/station.png" />
								<Format>image/png</Format>
							</ExternalGraphic>
							<Size>18</Size>
						</Graphic>
					</PointSymbolizer>
				</Rule>
				<Rule>
					<MaxScaleDenominator>10000</MaxScaleDenominator>
					<PointSymbolizer>
						<Graphic>
							<ExternalGraphic>
								<OnlineResource xlink:type="simple" xlink:href="images/station.png" />
								<Format>image/png</Format>
							</ExternalGraphic>
							<Size>22</Size>
						</Graphic>
					</PointSymbolizer>
					<TextSymbolizer>
						<Label>
							<ogc:PropertyName>dien_giai</ogc:PropertyName>
						</Label>
						<Font>
							<CssParameter name="font-family">Arial</CssParameter>
							<CssParameter name="font-size">10</CssParameter>
							<CssParameter name="font-style">normal</CssParameter>
						</Font>
						<Halo>
							<Radius>1</Radius>
							<Fill>
								<CssParameter name="fill">#ffffff</CssParameter>
							</Fill>
						</Halo>
						<VendorOption name="autoWrap">100</VendorOption>
						<LabelPlacement>
							<PointPlacement>
								<AnchorPoint>
									<AnchorPointX>0.5</AnchorPointX>
									<AnchorPointY>1</AnchorPointY>
								</AnchorPoint>
								<Displacement>
									<DisplacementX>0</DisplacementX>
									<DisplacementY>-13</DisplacementY>
								</Displacement>
							</PointPlacement>
						</LabelPlacement>
						<Fill>
							<CssParameter name="fill">#111111</CssParameter>
						</Fill>
					</TextSymbolizer>
				</Rule>
				<Rule>
					<MaxScaleDenominator>2000</MaxScaleDenominator>
					<PointSymbolizer>
						<Graphic>
							<ExternalGraphic>
								<OnlineResource xlink:type="simple" xlink:href="images/station.png" />
								<Format>image/png</Format>
							</ExternalGraphic>
							<Size>26</Size>
						</Graphic>
					</PointSymbolizer>
					<TextSymbolizer>
						<Label>
							<ogc:PropertyName>dien_giai</ogc:PropertyName>
						</Label>
						<Font>
							<CssParameter name="font-family">Arial</CssParameter>
							<CssParameter name="font-size">10</CssParameter>
							<CssParameter name="font-style">normal</CssParameter>
						</Font>
						<Halo>
							<Radius>1</Radius>
							<Fill>
								<CssParameter name="fill">#ffffff</CssParameter>
							</Fill>
						</Halo>
						<VendorOption name="autoWrap">100</VendorOption>
						<LabelPlacement>
							<PointPlacement>
								<AnchorPoint>
									<AnchorPointX>0.5</AnchorPointX>
									<AnchorPointY>1</AnchorPointY>
								</AnchorPoint>
								<Displacement>
									<DisplacementX>0</DisplacementX>
									<DisplacementY>-15</DisplacementY>
								</Displacement>
							</PointPlacement>
						</LabelPlacement>
						<Fill>
							<CssParameter name="fill">#111111</CssParameter>
						</Fill>
					</TextSymbolizer>
				</Rule>
			</FeatureTypeStyle>
		</UserStyle>
	</NamedLayer>
</StyledLayerDescriptor>