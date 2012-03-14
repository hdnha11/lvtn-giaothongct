<?xml version="1.0" encoding="ISO-8859-1"?>
<StyledLayerDescriptor version="1.0.0" 
		xsi:schemaLocation="http://www.opengis.net/sld StyledLayerDescriptor.xsd" 
		xmlns="http://www.opengis.net/sld" 
		xmlns:ogc="http://www.opengis.net/ogc" 
		xmlns:xlink="http://www.w3.org/1999/xlink" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<!-- a Named Layer is the basic building block of an SLD document -->
	<NamedLayer>
		<Name>quoc_lo</Name>
		<UserStyle>
		<!-- Styles can have names, titles and abstracts -->
			<Title>Quoc lo</Title>
			<Abstract>Trang tri va gan nhan cho quoc lo</Abstract>
			<!-- FeatureTypeStyles describe how to render different features -->
			<!-- A FeatureTypeStyle for rendering polygons -->
			<FeatureTypeStyle>
				<Rule>
					<Name>Lon</Name>
					<MaxScaleDenominator>20000</MaxScaleDenominator>
					<LineSymbolizer>
						<Stroke>
							<CssParameter name="stroke">#a17529</CssParameter>
							<CssParameter name="stroke-width">15</CssParameter>
							<CssParameter name="stroke-linecap">round</CssParameter>
						</Stroke>
					</LineSymbolizer>
				</Rule>
				<Rule>
					<Name>Vua</Name>
					<MinScaleDenominator>20000</MinScaleDenominator>
					<MaxScaleDenominator>120000</MaxScaleDenominator>
					<LineSymbolizer>
						<Stroke>
							<CssParameter name="stroke">#a17529</CssParameter>
							<CssParameter name="stroke-width">7</CssParameter>
							<CssParameter name="stroke-linecap">round</CssParameter>
						</Stroke>
					</LineSymbolizer>
				</Rule>
				<Rule>
					<Name>Nho</Name>
					<MinScaleDenominator>120000</MinScaleDenominator>
					<LineSymbolizer>
						<Stroke>
							<CssParameter name="stroke">#a17529</CssParameter>
							<CssParameter name="stroke-width">4</CssParameter>
							<CssParameter name="stroke-linecap">round</CssParameter>
						</Stroke>
					</LineSymbolizer>
				</Rule>
			</FeatureTypeStyle>
			<FeatureTypeStyle>
				<Rule>
					<Name>Lon</Name>
					<MaxScaleDenominator>20000</MaxScaleDenominator>
					<LineSymbolizer>
						<Stroke>
							<CssParameter name="stroke">#ffc445</CssParameter>
							<CssParameter name="stroke-width">13</CssParameter>
							<CssParameter name="stroke-linecap">round</CssParameter>
						</Stroke>
					</LineSymbolizer>
					<TextSymbolizer>
						<Label>
							<ogc:PropertyName>duong</ogc:PropertyName>
						</Label>
						<Font>
							<CssParameter name="font-family">Arial</CssParameter>
							<CssParameter name="font-size">12</CssParameter>
							<CssParameter name="font-style">normal</CssParameter>
						</Font>
						<LabelPlacement>
							<LinePlacement />
						</LabelPlacement>
						<VendorOption name="followLine">true</VendorOption>
					</TextSymbolizer>
				</Rule>
				<Rule>
					<Name>Vua</Name>
					<MinScaleDenominator>20000</MinScaleDenominator>
					<MaxScaleDenominator>120000</MaxScaleDenominator>
					<LineSymbolizer>
						<Stroke>
							<CssParameter name="stroke">#ffc445</CssParameter>
							<CssParameter name="stroke-width">5</CssParameter>
							<CssParameter name="stroke-linecap">round</CssParameter>
						</Stroke>
					</LineSymbolizer>
					<TextSymbolizer>
						<Label>
							<ogc:PropertyName>duong</ogc:PropertyName>
						</Label>
						<Font>
							<CssParameter name="font-family">Arial</CssParameter>
							<CssParameter name="font-size">12</CssParameter>
							<CssParameter name="font-style">normal</CssParameter>
						</Font>
						<Halo>
							<Radius>2</Radius>
							<Fill>
								<CssParameter name="fill">#ffc445</CssParameter>
							</Fill>
						</Halo>
						<LabelPlacement>
							<LinePlacement />
						</LabelPlacement>
						<VendorOption name="followLine">true</VendorOption>
					</TextSymbolizer>
				</Rule>
				<Rule>
					<Name>Nho</Name>
					<MinScaleDenominator>120000</MinScaleDenominator>
					<LineSymbolizer>
						<Stroke>
							<CssParameter name="stroke">#ffc445</CssParameter>
							<CssParameter name="stroke-width">2</CssParameter>
							<CssParameter name="stroke-linecap">round</CssParameter>
						</Stroke>
					</LineSymbolizer>
					<TextSymbolizer>
						<Label>
							<ogc:PropertyName>duong</ogc:PropertyName>
						</Label>
						<Font>
							<CssParameter name="font-family">Arial</CssParameter>
							<CssParameter name="font-size">12</CssParameter>
							<CssParameter name="font-style">normal</CssParameter>
						</Font>
						<Halo>
							<Radius>2</Radius>
							<Fill>
								<CssParameter name="fill">#ffc445</CssParameter>
							</Fill>
						</Halo>
						<LabelPlacement>
							<LinePlacement />
						</LabelPlacement>
						<VendorOption name="followLine">true</VendorOption>
					</TextSymbolizer>
				</Rule>
			</FeatureTypeStyle>
		</UserStyle>
	</NamedLayer>
</StyledLayerDescriptor>