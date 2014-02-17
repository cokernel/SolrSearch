<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=80; */

/**
 * @package     omeka
 * @subpackage  solr-search
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

class ResultsControllerTest_Query extends SolrSearch_Test_AppTestCase
{


    protected $_isAdminTest = false;


    /**
     * The simple sarch form should point to the Solr Search interceptor.
     */
    public function testOverrideSearchFormAction()
    {

        $this->dispatch('');

        // Get the interceptor route.
        $url = url('solr-search/results/interceptor');

        // Should override the default search action.
        $this->assertXpath('//form[@id="search-form"][@action="'.$url.'"]');

    }


    /**
     * The interceptor action should redirect to the results view and populate
     * the `solrq` GET parameter with the search query.
     */
    public function testRedirectToResults()
    {

        // Set the search query.
        $this->request->setMethod('GET')->setParam('query', 'query');

        // Run the search.
        $this->dispatch('solr-search/results/interceptor');

        // Should redirect to the results action.
        $this->assertRedirectTo('/solr-search/results?solrq=query');

    }


    /**
     * The current query should be populated in the search box.
     */
    public function testPopulateSearchBox()
    {

        // Set the query.
        $_GET['solrq'] = 'query';

        // Search.
        $this->dispatch('solr-search/results');

        // Should populate the search box.
        $this->assertXpath('//input[@name="solrq"][@value="query"]');

    }


    /**
     * The query should be listed as an applied filter paramter.
     */
    public function testShowFilterParameter()
    {

        // Set the query.
        $_GET['solrq'] = 'query';

        // Search.
        $this->dispatch('solr-search/results');

        // Should list the query.
        $this->assertXpathContentContains(
            '//span[@class="filterValue"]', 'query'
        );

    }


    /**
     * When an empty query is entered, a filter parameter labelled "ALL TERMS"
     * should be displayed 
     */
    public function testShowAllTermsForEmptyQuery()
    {

        // Search for nothing.
        $this->dispatch('solr-search/results');

        // Should list "ALL TERMS" filter.
        $this->assertXpathContentContains(
            '//span[@class="filterValue"]', __('ALL TERMS')
        );

    }


    /**
     * When an empty query is entered, all documents should be listed.
     */
    public function testReturnAllDocumentsForEmptyQuery()
    {

        // Search for nothing.
        $this->dispatch('solr-search/results');

    }


}
