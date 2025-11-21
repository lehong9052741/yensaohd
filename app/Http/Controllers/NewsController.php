<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        return view('news.index', [
            'news' => $news->items(),
            'page' => $news->currentPage(),
            'lastPage' => $news->lastPage(),
            'total' => $news->total()
        ]);
    }
    
    public function show($slug)
    {
        $article = News::where('slug', $slug)->firstOrFail();
        
        // Increment view count
        $article->increment('views');
        
        // Parse content to convert price tables to HTML
        $article->content = $this->parseContentToHtml($article->content);
        
        // Get related articles (same category)
        $relatedNews = News::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('news.show', compact('article', 'relatedNews'));
    }
    
    private function parseContentToHtml($content)
    {
        // Convert price information to table format
        // Pattern: Giá... : số tiền
        $lines = explode("\n", $content);
        $inPriceSection = false;
        $priceData = [];
        $result = [];
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // Detect price section patterns
            if (preg_match('/^(Bảng giá|Giá.*:|.*giá.*:)/i', $trimmed)) {
                $inPriceSection = true;
                $result[] = $line;
                continue;
            }
            
            // Detect price line: - Product name: price
            if ($inPriceSection && preg_match('/^-\s*(.+?):\s*(.+?)$/u', $trimmed, $matches)) {
                $priceData[] = [
                    'product' => trim($matches[1]),
                    'price' => trim($matches[2])
                ];
            } else {
                // End of price section
                if ($inPriceSection && count($priceData) > 0) {
                    $result[] = $this->generatePriceTable($priceData);
                    $priceData = [];
                    $inPriceSection = false;
                }
                $result[] = $line;
            }
        }
        
        // Handle remaining price data
        if (count($priceData) > 0) {
            $result[] = $this->generatePriceTable($priceData);
        }
        
        return implode("\n", $result);
    }
    
    private function generatePriceTable($priceData)
    {
        $html = '<table class="table table-hover">';
        $html .= '<thead><tr><th>Sản phẩm</th><th>Giá</th></tr></thead>';
        $html .= '<tbody>';
        
        foreach ($priceData as $item) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($item['product']) . '</td>';
            $html .= '<td>' . htmlspecialchars($item['price']) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }
}
