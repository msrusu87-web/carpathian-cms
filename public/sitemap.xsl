<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>XML Sitemap - Carpathian CMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #d32f2f;
            border-bottom: 3px solid #d32f2f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .info {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #d32f2f;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        a {
            color: #d32f2f;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .count {
            color: #666;
            font-size: 16px;
            margin: 10px 0;
        }
        .priority {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        .priority-high { background: #d4edda; color: #155724; }
        .priority-medium { background: #fff3cd; color: #856404; }
        .priority-low { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗺️ XML Sitemap - Carpathian CMS</h1>
        <div class="info">
            <strong>ℹ️ What is this?</strong><br/>
            This is an XML Sitemap for search engines like Google, Bing, and AI crawlers. 
            It helps them discover and index all pages on this website efficiently.
        </div>
        <p class="count">
            <strong>Total URLs:</strong> <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/>
        </p>
        <table>
            <tr>
                <th>URL</th>
                <th>Last Modified</th>
                <th>Change Frequency</th>
                <th>Priority</th>
            </tr>
            <xsl:for-each select="sitemap:urlset/sitemap:url">
            <tr>
                <td>
                    <xsl:variable name="itemURL">
                        <xsl:value-of select="sitemap:loc"/>
                    </xsl:variable>
                    <a href="{$itemURL}">
                        <xsl:value-of select="sitemap:loc"/>
                    </a>
                </td>
                <td>
                    <xsl:value-of select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
                </td>
                <td>
                    <xsl:value-of select="sitemap:changefreq"/>
                </td>
                <td>
                    <xsl:variable name="priority" select="sitemap:priority"/>
                    <xsl:choose>
                        <xsl:when test="$priority &gt; 0.7">
                            <span class="priority priority-high"><xsl:value-of select="$priority"/></span>
                        </xsl:when>
                        <xsl:when test="$priority &gt; 0.4">
                            <span class="priority priority-medium"><xsl:value-of select="$priority"/></span>
                        </xsl:when>
                        <xsl:otherwise>
                            <span class="priority priority-low"><xsl:value-of select="$priority"/></span>
                        </xsl:otherwise>
                    </xsl:choose>
                </td>
            </tr>
            </xsl:for-each>
        </table>
    </div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
