<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Google News Sitemap - Carpathian CMS</title>
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
            color: #1a73e8;
            border-bottom: 3px solid #1a73e8;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .info {
            background: #e8f0fe;
            border-left: 4px solid #1a73e8;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .warning {
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
            background: #1a73e8;
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
            color: #1a73e8;
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
        .keywords {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📰 Google News Sitemap - Carpathian CMS</h1>
        <div class="info">
            <strong>ℹ️ Google News Sitemap</strong><br/>
            This sitemap contains recent blog posts (last 48 hours) formatted for Google News. 
            It helps Google News discover and index fresh content quickly.
        </div>
        <p class="count">
            <strong>Total Articles:</strong> <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/>
        </p>
        <xsl:choose>
            <xsl:when test="count(sitemap:urlset/sitemap:url) = 0">
                <div class="warning">
                    <strong>⚠️ No Recent News Articles</strong><br/>
                    This sitemap is currently empty because no blog posts have been published in the last 48 hours.
                    The sitemap will automatically populate when new articles are published.
                </div>
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <h3>No articles to display</h3>
                    <p>Publish a new blog post to see it appear here within 48 hours.</p>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <table>
                    <tr>
                        <th>Article</th>
                        <th>Published</th>
                        <th>Keywords</th>
                    </tr>
                    <xsl:for-each select="sitemap:urlset/sitemap:url">
                    <tr>
                        <td>
                            <xsl:variable name="itemURL">
                                <xsl:value-of select="sitemap:loc"/>
                            </xsl:variable>
                            <a href="{$itemURL}">
                                <strong><xsl:value-of select="news:news/news:title"/></strong>
                            </a>
                        </td>
                        <td>
                            <xsl:value-of select="concat(substring(news:news/news:publication_date,0,11),concat(' ', substring(news:news/news:publication_date,12,5)))"/>
                        </td>
                        <td>
                            <span class="keywords">
                                <xsl:value-of select="news:news/news:keywords"/>
                            </span>
                        </td>
                    </tr>
                    </xsl:for-each>
                </table>
            </xsl:otherwise>
        </xsl:choose>
    </div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
