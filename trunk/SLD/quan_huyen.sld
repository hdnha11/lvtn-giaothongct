<?xml version="1.0" encoding="ISO-8859-1"?>
<StyledLayerDescriptor version="1.0.0"
		xsi:schemaLocation="http://www.opengis.net/sld StyledLayerDescriptor.xsd"
		xmlns="http://www.opengis.net/sld"
		xmlns:ogc="http://www.opengis.net/ogc"
		xmlns:xlink="http://www.w3.org/1999/xlink"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<!-- a Named Layer is the basic building block of an SLD document -->
	<NamedLayer>
		<Name>quan_huyen</Name>
		<UserStyle>
		<!-- Styles can have names, titles and abstracts -->
			<Title>Mau quan huyen</Title>
			<Abstract>To mau cho cac quan huyen</Abstract>
			<!-- FeatureTypeStyles describe how to render different features -->
			<!-- A FeatureTypeStyle for rendering polygons -->
			<FeatureTypeStyle>
				<Rule>
					<Name>GhiNhan</Name>
					<Title>Ghi nhan Quan huyen</Title>
					<TextSymbolizer>
						<Label>
							<ogc:PropertyName>ten</ogc:PropertyName>
						</Label>
						<Font>
							<CssParameter name="font-family">Arial</CssParameter>
							<CssParameter name="font-size">12</CssParameter>
							<CssParameter name="font-style">normal</CssParameter>
						</Font>
						<Halo>
							<Radius>2</Radius>
							<Fill>
								<CssParameter name="fill">#ffffff</CssParameter>
							</Fill>
						</Halo>
					</TextSymbolizer>
				</Rule>
				<Rule>
					<Name>CaiRang</Name>
					<Title>GID = 1</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.1"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#afe3a3</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
				<Rule>
					<Name>NinhKieu</Name>
					<Title>GID = 2</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.2"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#f5c9ec</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
				<Rule>
					<Name>BinhThuy</Name>
					<Title>GID = 3</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.3"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#ffffc7</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
				<Rule>
					<Name>OMon</Name>
					<Title>GID = 4</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.4"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#ccb2d5</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
				<Rule>
					<Name>CoDo</Name>
					<Title>GID = 5</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.5"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#d9fa93</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
				<Rule>
					<Name>VinhThanh</Name>
					<Title>GID = 6</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.6"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#fcffc8</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
				<Rule>
					<Name>ThotNot</Name>
					<Title>GID = 7</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.7"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#efd9a3</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
				<Rule>
					<Name>PhongDien</Name>
					<Title>GID = 8</Title>
					<ogc:Filter>
						<ogc:FeatureId fid="quan_huyen_region.8"/>
					</ogc:Filter>
					<PolygonSymbolizer>
						<Fill>
							<CssParameter name="fill">#efbab5</CssParameter>
						</Fill>
					</PolygonSymbolizer>
				</Rule>
			</FeatureTypeStyle>
		</UserStyle>
	</NamedLayer>
</StyledLayerDescriptor>