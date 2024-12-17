/*Author: Justine Lucas*/
package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	"path/filepath"
)

func fileHandler(w http.ResponseWriter, r *http.Request) {
	if r.URL.Path == "/" {
		http.ServeFile(w, r, "./static/index.html")
		return
	}

	filePath := filepath.Join("./static", r.URL.Path)
	if _, err := os.Stat(filePath); os.IsNotExist(err) {
		http.NotFound(w, r)
		http.Error(w, "Sorry for that !", http.StatusNotFound)
		return
	}
	http.ServeFile(w, r, filePath)
	fmt.Fprintf(w, "Hello!")
}

func main() {
	// Serve static files from the "static" directory
	fs := http.FileServer(http.Dir("./static"))
	http.Handle("/static/", http.StripPrefix("/static", fs))

	// Serve index.html for the main page
	http.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
		http.ServeFile(w, r, "./static/index.html")
	})

	// Start the Server
	fmt.Println("Starting server at http://Gogora")
	if err := http.ListenAndServe("192.168.68.126:8080", nil); err != nil {
		log.Fatal(err)
	}
}
