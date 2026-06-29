import os
import PyPDF2

pdf_path = r"c:\Users\zoomy\Downloads\BA\pruebas-calidad-grupo-03-feat_LMS_v2.0\pruebas-calidad-grupo-03-f\documentacion\COMPLETADOS\Proyecto Final-G3.docx.pdf"
output_path = r"c:\Users\zoomy\Downloads\BA\pruebas-calidad-grupo-03-feat_LMS_v2.0\pruebas-calidad-grupo-03-f\documentacion\COMPLETADOS\extracted_proyecto.md"

with open(output_path, 'w', encoding='utf-8') as out_f:
    try:
        with open(pdf_path, 'rb') as f:
            reader = PyPDF2.PdfReader(f)
            num_pages = len(reader.pages)
            
            # Extract first 15 pages to get the index and initial chapters
            # and maybe sample some later pages.
            pages_to_extract = list(range(min(20, num_pages)))
            
            for p in pages_to_extract:
                out_f.write(f"\n--- Page {p+1} ---\n")
                out_f.write(reader.pages[p].extract_text() + "\n")
    except Exception as e:
        out_f.write(f"Error reading PDF: {e}\n")

print("Done extracting to extracted_proyecto.md")
