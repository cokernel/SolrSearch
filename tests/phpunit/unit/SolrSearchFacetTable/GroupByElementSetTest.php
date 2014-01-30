<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=80; */

/**
 * @package     omeka
 * @subpackage  solr-search
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


class SolrSearchFacetTableTest_GroupByElementSet
    extends SolrSearch_Test_AppTestCase
{

    /**
     * Delete any facet mappings registered when the plugin is installed.
     */
    public function setUp()
    {
        parent::setUp();
        $this->_clearFacetMappings();
    }

    /**
     * `groupByElementSet` should return the facets grouped by element set.
     */
    public function testGroupByElementSet()
    {

        // Get the "Dublin Core" element set. 
        $dublinCore = $this->elementSetTable->findByName(
            'Dublin Core'
        );

        // Get the "Item Type Metadata" element set.
        $itemTypeMetadata = $this->elementSetTable->findByName(
            'Item Type Metadata'
        );

        // Get the Dublin Core "Date" field.
        $date = $this->elementTable->findByElementSetNameAndElementName(
            'Dublin Core', 'Date'
        );

        // Get the Dublin Core "Coverage" field.
        $coverage = $this->elementTable->findByElementSetNameAndElementName(
            'Dublin Core', 'Coverage'
        );

        // Get the Item Type Metadata "To" field.
        $to = $this->elementTable->findByElementSetNameAndElementName(
            'Item Type Metadata', 'To'
        );

        // Get the Item Type Metadata "From" field.
        $from = $this->elementTable->findByElementSetNameAndElementName(
            'Item Type Metadata', 'From'
        );

        // Facet with no element:
        $noElementSetFacet1 = new SolrSearchFacet();
        $noElementSetFacet1->name   = 'no_element_set_1';
        $noElementSetFacet1->label  = 'No Element Set 1';
        $noElementSetFacet1->save();

        // Facet with no element:
        $noElementSetFacet2 = new SolrSearchFacet();
        $noElementSetFacet2->name   = 'no_element_set_2';
        $noElementSetFacet2->label  = 'No Element Set 2';
        $noElementSetFacet2->save();

        // Facet for Dublin Core element:
        $dublinCoreFacet1 = new SolrSearchFacet();
        $dublinCoreFacet1->name         = 'dublin_core_1';
        $dublinCoreFacet1->label        = 'Dublin Core 1';
        $dublinCoreFacet1->element_id   = $date->id;
        $dublinCoreFacet1->save();

        // Facet for Dublin Core element:
        $dublinCoreFacet2 = new SolrSearchFacet();
        $dublinCoreFacet2->name         = 'dublin_core_2';
        $dublinCoreFacet2->label        = 'Dublin Core 2';
        $dublinCoreFacet2->element_id   = $coverage->id;
        $dublinCoreFacet2->save();

        // Facet for Item Type Metadata element:
        $itemTypeMetadataFacet1 = new SolrSearchFacet();
        $itemTypeMetadataFacet1->name       = 'item_type_metadata_1';
        $itemTypeMetadataFacet1->label      = 'Item Type Metadata 1';
        $itemTypeMetadataFacet1->element_id = $to->id;
        $itemTypeMetadataFacet1->save();

        // Facet for Item Type Metadata element:
        $itemTypeMetadataFacet2 = new SolrSearchFacet();
        $itemTypeMetadataFacet2->name       = 'item_type_metadata_2';
        $itemTypeMetadataFacet2->label      = 'Item Type Metadata 2';
        $itemTypeMetadataFacet2->element_id = $from->id;
        $itemTypeMetadataFacet2->save();

        // Get the facet groups.
        $groups = $this->facetTable->groupByElementSet();

        // Should group Omeka category facets:

        $this->assertEquals(
            $noElementSetFacet1->id,
            $groups['Omeka Categories'][0]['id']
        );

        $this->assertEquals(
            $noElementSetFacet2->id,
            $groups['Omeka Categories'][1]['id']
        );

        // Should group Dublin Core facets:

        $this->assertEquals(
            $dublinCoreFacet1->id,
            $groups['Dublin Core'][0]['id']
        );

        $this->assertEquals(
            $dublinCoreFacet2->id,
            $groups['Dublin Core'][1]['id']
        );

        // Should group Item Type Metadata facets:

        $this->assertEquals(
            $itemTypeMetadataFacet1->id,
            $groups['Item Type Metadata'][0]['id']
        );

        $this->assertEquals(
            $itemTypeMetadataFacet2->id,
            $groups['Item Type Metadata'][1]['id']
        );

    }

}